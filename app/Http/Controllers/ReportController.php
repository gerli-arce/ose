<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Sales Report
    public function sales(Request $request)
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
            return $this->exportSalesCsv($query->get());
        }

        $sales = $query->paginate(20);
        $totals = [
            'total' => $query->sum('total'),
            'tax' => $query->sum('tax_total'),
            'subtotal' => $query->sum('subtotal') // Approximation
        ];

        return view('reports.sales', compact('sales', 'totals', 'startDate', 'endDate', 'status'));
    }

    private function exportSalesCsv($sales)
    {
        $fileName = 'sales_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = array('Fecha', 'Documento', 'Serie-Numero', 'Cliente', 'RUC/DNI', 'Moneda', 'Total', 'Estado');

        $callback = function() use($sales, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sales as $sale) {
                $row['Fecha']  = $sale->issue_date->format('d/m/Y');
                $row['Documento'] = $sale->documentType->name;
                $row['Serie-Numero'] = $sale->series->prefix . '-' . $sale->number;
                $row['Cliente'] = $sale->customer->name ?? $sale->customer->business_name;
                $row['RUC/DNI'] = $sale->customer->tax_id;
                $row['Moneda'] = $sale->currency->code;
                $row['Total'] = $sale->total;
                $row['Estado'] = $sale->status;

                fputcsv($file, array($row['Fecha'], $row['Documento'], $row['Serie-Numero'], $row['Cliente'], $row['RUC/DNI'], $row['Moneda'], $row['Total'], $row['Estado']));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // Products Report
    public function products(Request $request)
    {
        $currentCompany = Auth::user()->companies->first();
        
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $query = InvoiceItem::select(
                'product_id',
                DB::raw('sum(quantity) as total_qty'),
                DB::raw('sum(line_total) as total_amount')
            )
            ->whereHas('invoice', function($q) use ($currentCompany, $startDate, $endDate) {
                $q->where('company_id', $currentCompany->id)
                  ->whereBetween('issue_date', [$startDate, $endDate])
                  ->where('status', '!=', 'cancelled');
            })
            ->groupBy('product_id')
            ->orderByDesc('total_amount')
            ->with('product');

        if ($request->has('export')) {
            return $this->exportProductsCsv($query->get());
        }

        $products = $query->paginate(20);

        return view('reports.products', compact('products', 'startDate', 'endDate'));
    }

     private function exportProductsCsv($items)
    {
        $fileName = 'products_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [
            'Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$fileName", 'Pragma' => 'no-cache', 'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Expires' => '0'
        ];

        $callback = function() use($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Producto', 'Codigo', 'Cantidad Vendida', 'Total Vendido']);
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->product->name,
                    $item->product->code,
                    $item->total_qty,
                    number_format($item->total_amount, 2, '.', '')
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    // Customers Report
    public function customers(Request $request)
    {
        $currentCompany = Auth::user()->companies->first();
        $startDate = $request->input('start_date', date('Y-m-01'));
        $endDate = $request->input('end_date', date('Y-m-t'));

        $query = Invoice::select(
                'customer_id',
                DB::raw('count(*) as total_invoices'),
                DB::raw('sum(total) as total_amount')
            )
            ->where('company_id', $currentCompany->id)
            ->whereBetween('issue_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->groupBy('customer_id')
            ->orderByDesc('total_amount')
            ->with('customer');

         if ($request->has('export')) {
            return $this->exportCustomersCsv($query->get());
        }

        $customers = $query->paginate(20);

        return view('reports.customers', compact('customers', 'startDate', 'endDate'));
    }

    private function exportCustomersCsv($items)
    {
        $fileName = 'customers_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [ 'Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$fileName", 'Pragma' => 'no-cache', 'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Expires' => '0'];

        $callback = function() use($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Cliente', 'RUC', 'Nro Facturas', 'Total Comprado']);
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->customer->business_name ?? $item->customer->name,
                    $item->customer->tax_id,
                    $item->total_invoices,
                    number_format($item->total_amount, 2, '.', '')
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
