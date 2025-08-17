<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function exportInvoice(Order $order)
    {
        $order->load(['items.book', 'payment']);
        $pdf = Pdf::loadView('pdf.invoice', compact('order'));
        return $pdf->stream('invoice.pdf');
    }
}
