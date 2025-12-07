<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportReceivablesController extends Controller
{
    public function index(Request $request)
    {
        $currentCompany = Auth::user()->companies->first();
        $customerName = $request->input('customer');

        // Logic: Total - Paid
        // Assuming 'payments' relationship exists on Invoice (sales_payments table)
        // We select invoices where balance > 0
        
        $query = Invoice::where('company_id', $currentCompany->id)
            ->where('status', '!=', 'cancelled')
            ->with(['customer', 'payments'])
            ->select('sales_documents.*', 
                DB::raw('(total - (SELECT COALESCE(SUM(amount), 0) FROM sales_payments WHERE sales_payments.sales_document_id = sales_documents.id)) as balance')
            )
            ->having('balance', '>', 0.01); // Tolerance

        if ($customerName) {
            $query->whereHas('customer', function($q) use ($customerName) {
                $q->where('name', 'like', "%$customerName%")
                  ->orWhere('business_name', 'like', "%$customerName%");
            });
        }
        
        // Get all pending invoices
        $invoices = $query->get();
        
        // Group by Customer for the Report
        $receivables = $invoices->groupBy('customer_id')->map(function ($startInvoices, $customerId) {
            $customer = $startInvoices->first()->customer;
            return [
                'customer_name' => $customer->business_name ?? $customer->name,
                'customer_doc' => $customer->tax_id,
                'total_billed' => $startInvoices->sum('total'),
                'total_paid' => $startInvoices->sum('total') - $startInvoices->sum('balance'),
                'total_balance' => $startInvoices->sum('balance'),
                'invoices' => $startInvoices
            ];
        });

        if ($request->has('export')) {
            return $this->exportCsv($receivables);
        }

        return view('reports.receivables.index', compact('receivables', 'customerName'));
    }

    private function exportCsv($receivables)
    {
        $fileName = 'receivables_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [ 'Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$fileName", 'Pragma' => 'no-cache', 'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Expires' => '0'];

        $callback = function() use($receivables) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Cliente', 'RUC', 'Total Facturado (Pendientes)', 'Acuenta', 'Saldo Pendiente']);
            foreach ($receivables as $row) {
                fputcsv($file, [
                    $row['customer_name'],
                    $row['customer_doc'],
                    number_format($row['total_billed'], 2),
                    number_format($row['total_paid'], 2),
                    number_format($row['total_balance'], 2)
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
