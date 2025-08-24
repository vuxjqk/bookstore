<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Order;
use App\Models\User;
use App\Rules\PhoneNumber;
use Carbon\Carbon;
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
        $customers = User::select('id', 'name', 'phone')->get();
        $books = Book::select('id', 'title', 'sale_price')->get();
        return view('orders.create', compact('customers', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => ['required', 'string', new PhoneNumber],
            'discount_amount' => 'required|numeric|min:0',
            'items' => 'required|array',
            'items.*.book_id' => [
                'required',
                'exists:books,id',
                function ($attribute, $value, $fail) use ($request) {
                    $bookIds = collect($request->input('items'))->pluck('book_id');
                    if ($bookIds->count() !== $bookIds->unique()->count()) {
                        $fail('The ' . $attribute . ' has already been taken.');
                    }
                }
            ],
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        $bookIds = collect($validated['items'])->pluck('book_id');
        $books = Book::whereIn('id', $bookIds)
            ->select('id', 'title', 'sale_price', 'stock_quantity')
            ->get()
            ->keyBy('id');

        foreach ($validated['items'] as $item) {
            $book = $books->get($item['book_id']);
            if ($book->stock_quantity < $item['quantity']) {
                return redirect()->back()->with('error', __('Insufficient stock quantity for ') . $book->title . '!');
            }
        }

        try {
            $order = DB::transaction(function () use ($validated, $books) {
                $order = Order::create([
                    'customer_id' => $validated['customer_id'],
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'order_date' => now(),
                    'total_amount' => 0,
                    'discount_amount' => $validated['discount_amount'],
                    'status' => 'completed',
                    'employee_id' => Auth::id(),
                ]);

                $totalAmount = 0;
                foreach ($validated['items'] as $item) {
                    $book = $books->get($item['book_id']);
                    $unitPrice = $book->sale_price;
                    $subtotal = $item['quantity'] * $unitPrice;
                    $totalAmount += $subtotal;

                    $order->items()->create([
                        'book_id' => $book->id,
                        'quantity' => $item['quantity'],
                        'unit_price' => $unitPrice,
                        'subtotal' => $subtotal,
                    ]);

                    $book->decrement('stock_quantity', $item['quantity']);
                }
                $order->update(['total_amount' => $totalAmount]);

                return $order;
            });

            return redirect()->route('orders.show', $order)->with('success', __('Order created successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('An error occurred: ') . $e->getMessage());
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
    public function update(Order $order)
    {
        $statusFlow = ['pending', 'confirmed', 'processing', 'shipping', 'delivered', 'completed'];

        $currentIndex = array_search($order->status, $statusFlow);
        if ($currentIndex === false) {
            return redirect()->back()->with('error', __('Current status is invalid: ' . $order->status));
        }

        $nextStatus = $statusFlow[$currentIndex + 1] ?? null;

        if (!$nextStatus) {
            return redirect()->back()->with('error', __('Order is already in final status.'));
        }

        $order->update(['status' => $nextStatus]);

        return redirect()->back()->with('success', __('Order status updated to: ' . __($nextStatus)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', __('Cannot delete order because it is not in pending status.'));
        }

        try {
            $order->update([
                'status' => 'cancelled',
            ]);
            $order->delete();

            foreach ($order->items as $item) {
                $item->book->increment('stock_quantity', $item->quantity);
            }

            return redirect()->route('orders.index')->with('success', __('Order cancelled successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }

    public function restore($id)
    {
        $order = Order::withTrashed()->find($id);

        if (!$order) {
            return redirect()->back()->with('error', __('Order not found.'));
        }

        if ($order->trashed()) {
            $deletedAt = Carbon::parse($order->deleted_at);
            $oneHourAgo = Carbon::now()->subHour();

            if ($deletedAt >= $oneHourAgo) {
                $order->status = 'pending';
                $order->deleted_at = null;
                $order->save();

                foreach ($order->items as $item) {
                    $item->book->decrement('stock_quantity', $item->quantity);
                }

                return redirect()->route('orders.index')->with('success', __('Order restored successfully.'));
            } else {
                return redirect()->back()->with('error', __('You cannot restore this order as it was deleted more than 1 hour ago.'));
            }
        } else {
            return redirect()->back()->with('error', __('This order has not been deleted.'));
        }
    }

    public function saveOrder(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', __('Your cart is empty.'));
        }

        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => ['required', 'string', new PhoneNumber],
            'shipping_address' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'payment_method' => 'required|in:cod,bank_transfer,momo,vnpay,credit_card',
            'save_address' => 'nullable|boolean',
        ]);

        $discount_amount = 0;

        $total = collect($cart)->sum('subtotal');
        $shippingFee = $total > 300000 ? 0 : 30000;
        $total += $shippingFee;

        $bookIds = array_keys($cart);
        $books = Book::whereIn('id', $bookIds)
            ->select('id', 'title', 'stock_quantity')
            ->get()
            ->keyBy('id');

        foreach ($cart as $bookId => $item) {
            $book = $books->get($bookId);
            if ($book->stock_quantity < $item['quantity']) {
                return redirect()->route('cart.index')->with('error', __(__('Insufficient stock quantity for ') . $book->title . '!'));
            }
        }

        try {
            return DB::transaction(function () use ($validated, $cart, $total, $discount_amount, $books) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                if ($user && $validated['save_address']) {
                    $user->update([
                        'address' => $validated['shipping_address'],
                    ]);
                }

                $order = Order::create([
                    'customer_id' => Auth::id(),
                    'customer_name' => $validated['customer_name'],
                    'customer_phone' => $validated['customer_phone'],
                    'shipping_address' => $validated['shipping_address'],
                    'order_date' => now(),
                    'total_amount' => $total,
                    'discount_amount' => $discount_amount,
                    'status' => 'pending',
                ]);

                foreach ($cart as $bookId => $item) {
                    $order->items()->create([
                        'book_id' => $bookId,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $book = $books->get($item['book_id']);
                    $book->decrement('stock_quantity', $item['quantity']);
                }

                $order->payment()->create([
                    'payment_method' => $validated['payment_method'],
                    'amount' => $total,
                    'payment_status' => 'pending',
                    'paid_at' => null,
                    'transaction_id' => null,
                ]);

                session()->forget('cart');

                return redirect()->route('cart.success')->with('success', __('Order created successfully! Order ID: ' . $order->id));
            });
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('An error occurred while creating the order.') . $e->getMessage());
        }
    }
}
