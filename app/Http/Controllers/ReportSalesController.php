<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportSalesController extends Controller
{
    public function index(Request $request)
    {
        $currentCompany = Auth::user()->companies->first();
        
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));
        $status = $request->input('status');
        
        $query = Invoice::where('company_id', $currentCompany->id)
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->with(['customer', 'documentType', 'series']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($request->has('export')) {
            return $this->exportCsv($query->get());
        }

        $sales = $query->latest('issue_date')->paginate(20);
        $totals = [
            'total' => $query->sum('total'),
            'tax' => $query->sum('tax_total'),
            'subtotal' => $query->sum('subtotal')
        ];

        return view('reports.sales.index', compact('sales', 'totals', 'startDate', 'endDate', 'status'));
    }

    private function exportCsv($sales)
    {
        $fileName = 'sales_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = array('Fecha', 'Documento', 'Serie-Numero', 'Cliente', 'RUC/DNI', 'Moneda', 'Gravado', 'IGV', 'Total', 'Estado');

        $callback = function() use($sales, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->issue_date->format('d/m/Y'),
                    $sale->documentType->name,
                    $sale->series->prefix . '-' . $sale->number,
                    $sale->customer->name ?? $sale->customer->business_name,
                    $sale->customer->tax_id,
                    $sale->currency->code,
                    $sale->subtotal,
                    $sale->tax_total,
                    $sale->total,
                    $sale->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
