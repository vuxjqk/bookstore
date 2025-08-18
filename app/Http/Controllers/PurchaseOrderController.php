<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

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
            return redirect()
                ->back()
                ->with('error', __('Current status :status is invalid.', ['status' => $purchaseOrder->status]));
        }

        $nextStatus = $statusFlow[$currentIndex + 1] ?? null;

        if (!$nextStatus) {
            return redirect()
                ->back()
                ->with('error', __('Purchase order is already in final status.'));
        }

        $purchaseOrder->update(['status' => $nextStatus]);

        return redirect()
            ->back()
            ->with('success', __('Purchase order status advanced to :status.', ['status' => $nextStatus]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        try {
            $purchaseOrder->update([
                'status' => 'cancelled',
            ]);
            $purchaseOrder->delete();
            return redirect()->back()->with('success', __('Purchase order cancelled successfully.'));
        } catch (QueryException $e) {
            $msg = $e->getCode() === '23000'
                ? __('Cannot delete because it is in use.')
                : __('An error occurred while deleting: ') . $e->getMessage();

            return redirect()->back()->with('error', $msg);
        }
    }
}
