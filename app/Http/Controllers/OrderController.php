<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('include_deleted') && $request->boolean('include_deleted')) {
            $query = $query->withTrashed();
        }

        $orders = $query->filter($request->all())
            ->paginate(10)
            ->appends($request->query());

        $statistics = [
            'totalOrders'     => Order::count(),
            'totalProcessing' => Order::where('status', 'processing')->count(),
            'totalShipping'   => Order::where('status', 'shipping')->count(),
            'totalCompleted'  => Order::where('status', 'completed')->count(),
        ];

        return view('orders.index', compact('orders') + $statistics);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:100',
            'customer_phone' => ['required', 'string', 'regex:/^(?:\+84|0)(3[2-9]|7[0|6-9]|8[1-9]|9[0-9])[0-9]{7}$/', 'max:20'],
            'shipping_address' => 'required|string|max:255',
            'payment_method' => 'required|in:cod,bank_transfer,momo,vnpay,credit_card',
            'save_phone' => 'nullable|boolean',
            'save_address' => 'nullable|boolean',
        ]);

        $total = collect($cart)->sum('subtotal') + 30000;

        foreach ($cart as $bookId => $item) {
            $book = Book::select('id', 'stock_quantity')->findOrFail($bookId);
            if ($book->stock_quantity < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', __('Insufficient stock quantity for :title!', ['title' => $item['title']]));
            }
        }

        try {
            return DB::transaction(function () use ($validated, $cart, $total) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                if ($user && ($validated['save_phone'] || $validated['save_address'])) {
                    $updates = [];
                    if ($validated['save_phone']) {
                        $updates['customer_phone'] = $validated['customer_phone'];
                    }
                    if ($validated['save_address']) {
                        $updates['address'] = $validated['shipping_address'];
                    }
                    $user->update($updates);
                }

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'order_date' => now(),
                    'total_amount' => $total,
                    'status' => 'pending',
                ]);

                foreach ($cart as $bookId => $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'book_id' => $bookId,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $book = Book::findOrFail($bookId);
                    $book->decrement('stock_quantity', $item['quantity']);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'amount' => $total,
                    'payment_status' => 'pending',
                    'paid_at' => now(),
                    'transaction_id' => null,
                ]);

                session()->forget('cart');

                return redirect()->route('cart.success')->with('success', __('Order created successfully! Order ID: :id', ['id' => $order->id]));
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('An error occurred while creating the order.') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load(['items.book', 'payment']);
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipping,delivered,completed,cancelled,refunded,failed',
        ]);

        $order->update($validated);

        return redirect()->back()->with('success', __('Order status updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        try {
            $order->update([
                'status' => 'cancelled',
            ]);
            $order->delete();
            return redirect()->back()->with('success', __('Order cancelled successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }
}
