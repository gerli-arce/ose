<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class QuotationPdfController extends Controller
{
    public function download(Quotation $quotation)
    {
        $companyId = session('current_company_id');
        
        if ($quotation->company_id !== $companyId) {
            abort(403);
        }

        $quotation->load(['company', 'customer', 'currency', 'items.product', 'seller']);

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        
        $filename = "Cotizacion_{$quotation->full_number}.pdf";
        
        return $pdf->download($filename);
    }

    public function stream(Quotation $quotation)
    {
        $companyId = session('current_company_id');
        
        if ($quotation->company_id !== $companyId) {
            abort(403);
        }

        $quotation->load(['company', 'customer', 'currency', 'items.product', 'seller']);

        $pdf = Pdf::loadView('quotations.pdf', compact('quotation'));
        
        return $pdf->stream("Cotizacion_{$quotation->full_number}.pdf");
    }
}
