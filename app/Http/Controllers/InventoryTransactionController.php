<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $purchaseOrderIds = PurchaseOrder::whereNull('deleted_at')->pluck('id')->toArray();
        $orderIds = Order::whereNull('deleted_at')->pluck('id')->toArray();

        $purchaseOrderItemIds = PurchaseOrderItem::whereIn('purchase_order_id', $purchaseOrderIds)->pluck('id')->toArray();
        $orderItemIds = OrderItem::whereIn('order_id', $orderIds)->pluck('id')->toArray();

        $transactions = InventoryTransaction::with([
            'purchase_order_item.book',
            'order_item.book'
        ])
            ->whereIn('purchase_order_item_id', $purchaseOrderItemIds)
            ->orWhereIn('order_item_id', $orderItemIds)
            ->filter($request->all())
            ->paginate(10)
            ->appends($request->query());

        $totalTransactions = InventoryTransaction::count();
        $totalIncoming = InventoryTransaction::where('transaction_type', 'in')->sum('quantity');
        $totalOutgoing = InventoryTransaction::where('transaction_type', 'out')->sum('quantity');
        $netStockChange = $totalIncoming - $totalOutgoing;

        return view('inventory_transactions.index', compact(
            'transactions',
            'totalTransactions',
            'totalIncoming',
            'totalOutgoing',
            'netStockChange'
        ));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryTransaction $inventoryTransaction)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryTransaction $inventoryTransaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryTransaction $inventoryTransaction)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $inventoryTransaction->update($validated);
        return redirect()->back()->with('success', __('Notes updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        //
    }
}
