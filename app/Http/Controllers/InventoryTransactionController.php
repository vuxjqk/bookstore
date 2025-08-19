<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use Illuminate\Http\Request;

class InventoryTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = InventoryTransaction::with([
            'purchase_order_item.book',
            'order_item.book'
        ])
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        //
    }
}
