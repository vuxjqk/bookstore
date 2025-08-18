<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PurchaseOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function exportOrderInvoice(Order $order)
    {
        $order->load(['items.book', 'payment']);
        $pdf = Pdf::loadView('pdf.order_invoice', compact('order'));
        return $pdf->stream('invoice.pdf');
    }

    public function exportPurchaseOrderInvoice(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['items.book']);
        $pdf = Pdf::loadView('pdf.purchase_order_invoice', ['order' => $purchaseOrder]);
        return $pdf->stream('invoice.pdf');
    }
}
