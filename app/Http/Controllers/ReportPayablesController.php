<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportPayablesController extends Controller
{
    public function index(Request $request)
    {
        $currentCompany = Auth::user()->companies->first();
        $supplierName = $request->input('supplier');

        // Similar logic for Purchases
        
        $query = PurchaseDocument::where('company_id', $currentCompany->id)
            ->where('status', '!=', 'cancelled') // Assuming active
            ->with(['supplier', 'payments'])
            ->select('purchase_documents.*', 
                DB::raw('(total - (SELECT COALESCE(SUM(amount), 0) FROM purchase_payments WHERE purchase_payments.purchase_document_id = purchase_documents.id)) as balance')
            )
            ->having('balance', '>', 0.01);

        if ($supplierName) {
            $query->whereHas('supplier', function($q) use ($supplierName) {
                $q->where('name', 'like', "%$supplierName%")
                  ->orWhere('business_name', 'like', "%$supplierName%");
            });
        }
        
        $documents = $query->get();
        
        $payables = $documents->groupBy('supplier_id')->map(function ($items, $id) {
            $supplier = $items->first()->supplier;
            return [
                'supplier_name' => $supplier->business_name ?? $supplier->name,
                'supplier_doc' => $supplier->tax_id,
                'total_billed' => $items->sum('total'),
                'total_paid' => $items->sum('total') - $items->sum('balance'),
                'total_balance' => $items->sum('balance'),
                'documents' => $items
            ];
        });

        if ($request->has('export')) {
            return $this->exportCsv($payables);
        }

        return view('reports.payables.index', compact('payables', 'supplierName'));
    }

    private function exportCsv($payables)
    {
        $fileName = 'payables_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [ 'Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$fileName", 'Pragma' => 'no-cache', 'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Expires' => '0'];

        $callback = function() use($payables) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Proveedor', 'RUC', 'Total Compra (Pendientes)', 'Pagado', 'Saldo Pendiente']);
            foreach ($payables as $row) {
                fputcsv($file, [
                    $row['supplier_name'],
                    $row['supplier_doc'],
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
