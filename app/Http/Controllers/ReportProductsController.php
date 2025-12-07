<?php

namespace App\Http\Controllers;

use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportProductsController extends Controller
{
    public function index(Request $request)
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
            return $this->exportCsv($query->get());
        }

        $products = $query->paginate(20);

        return view('reports.products.index', compact('products', 'startDate', 'endDate'));
    }

    private function exportCsv($items)
    {
        $fileName = 'products_report_' . date('Y-m-d_H-i') . '.csv';
        $headers = [ 'Content-type' => 'text/csv', 'Content-Disposition' => "attachment; filename=$fileName", 'Pragma' => 'no-cache', 'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Expires' => '0'];

        $callback = function() use($items) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Producto', 'Codigo', 'Cantidad Vendida', 'Total Vendido']);
            foreach ($items as $item) {
                fputcsv($file, [
                    $item->product->name,
                    $item->product->code ?? '',
                    $item->total_qty,
                    number_format($item->total_amount, 2, '.', '')
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }
}
