<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;


class SaleController extends Controller
{
    public function download(Sale $sale)
    {
        $pdf = Pdf::loadView('pdf.invoice', ['sale' => $sale]);
        return $pdf->download('Invoice_' . $sale->id . '.pdf');
    }

    public function preview(Sale $sale)
    {
        $pdf = Pdf::loadView('pdf.invoice', ['sale' => $sale]);
        return $pdf->stream('Invoice_' . $sale->id . '.pdf');
    }
}
