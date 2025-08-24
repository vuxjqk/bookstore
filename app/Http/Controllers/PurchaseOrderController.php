<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with('supplier:id,name');

        if ($request->has('include_deleted') && $request->boolean('include_deleted')) {
            $query = $query->withTrashed();
        }

        $orders = $query->filter($request->all())
            ->paginate(10)
            ->appends($request->query());

        $suppliers = Supplier::all();

        $statistics = [
            'totalOrders'    => PurchaseOrder::withTrashed()->count(),
            'totalPending'   => PurchaseOrder::where('status', 'pending')->count(),
            'totalConfirmed' => PurchaseOrder::where('status', 'confirmed')->count(),
            'totalReceived'  => PurchaseOrder::where('status', 'received')->count(),
        ];

        return view('purchase_orders.index', compact('orders', 'suppliers') + $statistics);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $suppliers = Supplier::select('id', 'name')->get();
        $books = Book::select('id', 'title')->get();
        return view('purchase_orders.create', compact('suppliers', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'discount_amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
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
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                $order = PurchaseOrder::create([
                    'supplier_id' => $validated['supplier_id'],
                    'order_date' => now(),
                    'total_amount' => 0,
                    'discount_amount' => $validated['discount_amount'],
                    'status' => 'pending',
                    'notes' => $validated['notes'],
                    'employee_id' => Auth::id(),
                ]);

                $totalAmount = 0;
                foreach ($validated['items'] as $item) {
                    $subtotal = $item['quantity'] * $item['unit_price'];
                    $totalAmount += $subtotal;

                    $order->items()->create([
                        'book_id' => $item['book_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'subtotal' => $subtotal,
                    ]);
                }
                $order->update(['total_amount' => $totalAmount]);

                return $order;
            });

            return redirect()->route('purchase_orders.show', $order)->with('success', __('Purchase order created successfully.'));
        } catch (Exception $e) {
            return redirect()->back()->with('error', __('An error occurred: ') . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier:id,name', 'items.book']);
        return view('purchase_orders.show', ['order' => $purchaseOrder]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PurchaseOrder $purchaseOrder)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseOrder $purchaseOrder)
    {
        $statusFlow = ['pending', 'confirmed', 'received'];

        $currentIndex = array_search($purchaseOrder->status, $statusFlow);
        if ($currentIndex === false) {
            return redirect()->back()->with('error', __('Current status is invalid: ' . $purchaseOrder->status));
        }

        $nextStatus = $statusFlow[$currentIndex + 1] ?? null;

        if (!$nextStatus) {
            return redirect()->back()->with('error', __('Purchase order is already in final status.'));
        }

        $purchaseOrder->update(['status' => $nextStatus]);

        if ($nextStatus === 'received') {
            foreach ($purchaseOrder->items as $item) {
                $item->book->increment('stock_quantity', $item->quantity);
            }
        }

        return redirect()->back()->with('success', __('Purchase order status updated to: ' . __($nextStatus)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return redirect()->back()->with('error', __('Cannot delete purchase order because it is not in pending status.'));
        }

        try {
            $purchaseOrder->update([
                'status' => 'cancelled',
            ]);
            $purchaseOrder->delete();
            return redirect()->route('purchase_orders.index')->with('success', __('Purchase order deleted successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }

    public function restore($id)
    {
        $purchaseOrder = PurchaseOrder::withTrashed()->find($id);

        if (!$purchaseOrder) {
            return redirect()->back()->with('error', __('Purchase order not found.'));
        }

        if ($purchaseOrder->trashed()) {
            $deletedAt = Carbon::parse($purchaseOrder->deleted_at);
            $oneHourAgo = Carbon::now()->subHour();

            if ($deletedAt >= $oneHourAgo) {
                $purchaseOrder->status = 'pending';
                $purchaseOrder->deleted_at = null;
                $purchaseOrder->save();

                return redirect()->route('purchase_orders.index')->with('success', __('Purchase order restored successfully.'));
            } else {
                return redirect()->back()->with('error', __('You cannot restore this purchase order as it was deleted more than 1 hour ago.'));
            }
        } else {
            return redirect()->back()->with('error', __('This purchase order has not been deleted.'));
        }
    }
}
