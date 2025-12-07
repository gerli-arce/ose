<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportTaxesController extends Controller
{
    public function index(Request $request)
    {
        $currentCompany = Auth::user()->companies->first();
        
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        
        // Only active documents
        $query = Invoice::where('company_id', $currentCompany->id)
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled'); // Assuming cancelled/anulado doesn't count

        if ($request->has('export')) {
            return $this->exportCsv($query->get());
        }

        // Summary Grouping
        // We need: Base Imponible (Gravado), Exonerado, IGV, Total
        // If tax_total > 0, assume Gravado. If 0, assume Exonerado (simplification).
        
        $sales = $query->with(['documentType', 'series', 'customer'])->orderBy('issue_date')->paginate(20);
        
        // Calculate totals manually
        $totals = [
            'total_taxable' => 0,
            'total_exempt' => 0,
            'total_igv' => 0,
            'total' => 0
        ];
        
        // Note: For large datasets, use DB aggregation. For pagination view, summing paginated items is wrong for total summary.
        // We should sum the whole query.
        $allData = $query->get();
        foreach($allData as $inv) {
            $totals['total_igv'] += $inv->tax_total;
            $totals['total'] += $inv->total;
            if($inv->tax_total > 0) {
                $totals['total_taxable'] += $inv->subtotal;
            } else {
                $totals['total_exempt'] += $inv->subtotal; // or total
            }
        }

        return view('reports.taxes.index', compact('sales', 'totals', 'startDate', 'endDate'));
    }

    private function exportCsv($sales)
    {
        $fileName = 'tax_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Fecha', 'Doc', 'Serie-Num', 'Cliente', 'RUC', 'Gravado', 'Exonerado', 'IGV', 'Total'];

        $callback = function() use($sales, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sales as $sale) {
                $gravado = $sale->tax_total > 0 ? $sale->subtotal : 0;
                $exonerado = $sale->tax_total == 0 ? $sale->subtotal : 0; // Simple logic
                
                fputcsv($file, [
                    $sale->issue_date->format('d/m/Y'),
                    $sale->documentType->name,
                    $sale->series->prefix . '-' . $sale->number,
                    $sale->customer->name ?? $sale->customer->business_name,
                    $sale->customer->tax_id,
                    $gravado,
                    $exonerado,
                    $sale->tax_total,
                    $sale->total
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
