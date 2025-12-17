<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Contact;
use App\Models\DailySummary;
use App\Models\DespatchAdvice;
use App\Models\Product;
use App\Models\SalesDocument;
use App\Models\SalesDocumentItem;
use App\Models\VoidedDocument;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('current_company_id');
        
        if (!$companyId) {
            return redirect()->route('company.selection');
        }

        $company = Company::find($companyId);

        // ========== ESTADÍSTICAS DE VENTAS ==========
        
        $today = Carbon::today();
        $startOfWeek = Carbon::now()->startOfWeek();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endOfLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Ventas del día
        $salesToday = SalesDocument::where('company_id', $companyId)
            ->whereDate('issue_date', $today)
            ->where('status', '!=', 'cancelled')
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as total')
            ->first();

        // Ventas de la semana
        $salesWeek = SalesDocument::where('company_id', $companyId)
            ->whereBetween('issue_date', [$startOfWeek, Carbon::now()])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as total')
            ->first();

        // Ventas del mes
        $salesMonth = SalesDocument::where('company_id', $companyId)
            ->whereBetween('issue_date', [$startOfMonth, Carbon::now()])
            ->where('status', '!=', 'cancelled')
            ->selectRaw('COUNT(*) as count, COALESCE(SUM(total), 0) as total')
            ->first();

        // Ventas mes anterior (para comparativa)
        $salesLastMonth = SalesDocument::where('company_id', $companyId)
            ->whereBetween('issue_date', [$startOfLastMonth, $endOfLastMonth])
            ->where('status', '!=', 'cancelled')
            ->sum('total');

        // Calcular porcentaje de cambio
        $monthChange = 0;
        if ($salesLastMonth > 0) {
            $monthChange = round((($salesMonth->total - $salesLastMonth) / $salesLastMonth) * 100, 1);
        }

        // ========== GRÁFICOS ==========

        // Ventas últimos 30 días
        $salesLast30Days = SalesDocument::where('company_id', $companyId)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('issue_date', [Carbon::now()->subDays(30), Carbon::now()])
            ->selectRaw('DATE(issue_date) as date, SUM(total) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Rellenar días sin ventas
        $chartLabels = [];
        $chartValues = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $chartLabels[] = Carbon::parse($date)->format('d/m');
            $chartValues[] = $salesLast30Days->get($date)?->total ?? 0;
        }

        // Ventas por tipo de documento
        $salesByType = SalesDocument::where('company_id', $companyId)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('issue_date', [$startOfMonth, Carbon::now()])
            ->join('document_types', 'document_types.id', '=', 'sales_documents.document_type_id')
            ->selectRaw('document_types.name as type_name, document_types.code as type_code, COUNT(*) as count, SUM(sales_documents.total) as total')
            ->groupBy('document_types.id', 'document_types.name', 'document_types.code')
            ->get();

        $typeLabels = $salesByType->pluck('type_name')->toArray();
        $typeValues = $salesByType->pluck('total')->toArray();
        $typeCounts = $salesByType->pluck('count')->toArray();

        // Distribución por método de pago (desde pagos)
        $salesByPayment = DB::table('sales_payments')
            ->join('payment_methods', 'payment_methods.id', '=', 'sales_payments.payment_method_id')
            ->where('sales_payments.company_id', $companyId)
            ->whereBetween('sales_payments.payment_date', [$startOfMonth, Carbon::now()])
            ->selectRaw('payment_methods.name as method_name, SUM(sales_payments.amount) as total')
            ->groupBy('payment_methods.id', 'payment_methods.name')
            ->get();

        // Si no hay pagos, mostrar datos vacíos o por defecto
        if ($salesByPayment->isEmpty()) {
            $paymentLabels = ['Sin pagos registrados'];
            $paymentValues = [$salesMonth->total ?? 0];
        } else {
            $paymentLabels = $salesByPayment->pluck('method_name')->toArray();
            $paymentValues = $salesByPayment->pluck('total')->toArray();
        }


        // ========== DOCUMENTOS RECIENTES ==========

        $recentDocuments = SalesDocument::where('company_id', $companyId)
            ->with(['documentType', 'customer', 'eDocument'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // ========== ESTADO SUNAT ==========

        // Documentos pendientes de envío
        $pendingDocs = SalesDocument::where('company_id', $companyId)
            ->whereDoesntHave('eDocument')
            ->where('status', 'emitted')
            ->count();

        // Documentos rechazados
        $rejectedDocs = SalesDocument::where('company_id', $companyId)
            ->whereHas('eDocument', function($q) {
                $q->where('response_status', 'rejected');
            })
            ->count();

        // Resúmenes diarios pendientes
        $pendingSummaries = 0;
        try {
            if (Schema::hasTable('daily_summaries')) {
                $pendingSummaries = DailySummary::where('company_id', $companyId)
                    ->whereIn('status', ['pending', 'sent'])
                    ->count();
            }
        } catch (\Exception $e) {
            $pendingSummaries = 0;
        }

        // Guías pendientes
        $pendingDespatch = 0;
        try {
            if (Schema::hasTable('despatch_advices')) {
                $pendingDespatch = DespatchAdvice::where('company_id', $companyId)
                    ->where('sunat_status', 'pending')
                    ->count();
            }
        } catch (\Exception $e) {
            $pendingDespatch = 0;
        }


        // Verificar configuración SUNAT
        $sunatConfigured = $company && 
            $company->sunat_sol_user && 
            $company->sunat_sol_password && 
            $company->sunat_cert_path;

        // ========== TOP PRODUCTOS ==========

        $topProducts = SalesDocumentItem::select(
                'products.id',
                'products.name',
                DB::raw('SUM(sales_document_items.quantity) as total_qty'),
                DB::raw('SUM(sales_document_items.line_total) as total_amount')
            )
            ->join('sales_documents', 'sales_documents.id', '=', 'sales_document_items.sales_document_id')
            ->join('products', 'products.id', '=', 'sales_document_items.product_id')
            ->where('sales_documents.company_id', $companyId)
            ->where('sales_documents.status', '!=', 'cancelled')
            ->whereBetween('sales_documents.issue_date', [$startOfMonth, Carbon::now()])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        // ========== TOP CLIENTES ==========

        $topCustomers = SalesDocument::select(
                'contacts.id',
                'contacts.name',
                'contacts.tax_id',
                DB::raw('COUNT(*) as doc_count'),
                DB::raw('SUM(sales_documents.total) as total_amount')
            )
            ->join('contacts', 'contacts.id', '=', 'sales_documents.customer_id')
            ->where('sales_documents.company_id', $companyId)
            ->where('sales_documents.status', '!=', 'cancelled')
            ->whereBetween('sales_documents.issue_date', [$startOfMonth, Carbon::now()])
            ->groupBy('contacts.id', 'contacts.name', 'contacts.tax_id')
            ->orderByDesc('total_amount')
            ->limit(5)
            ->get();

        // ========== ALERTAS ==========

        $alerts = [];

        if (!$sunatConfigured) {
            $alerts[] = [
                'type' => 'warning',
                'icon' => 'fa-exclamation-triangle',
                'message' => 'Configure las credenciales SUNAT para enviar documentos electrónicos.',
                'action' => route('settings.sunat.index'),
                'action_text' => 'Configurar'
            ];
        }

        if ($rejectedDocs > 0) {
            $alerts[] = [
                'type' => 'danger',
                'icon' => 'fa-times-circle',
                'message' => "Tiene {$rejectedDocs} documento(s) rechazado(s) por SUNAT que requieren atención.",
                'action' => route('sales.documents.index', ['status' => 'rejected']),
                'action_text' => 'Ver'
            ];
        }

        if ($pendingDocs > 0) {
            $alerts[] = [
                'type' => 'info',
                'icon' => 'fa-clock-o',
                'message' => "Tiene {$pendingDocs} documento(s) pendiente(s) de envío a SUNAT.",
                'action' => route('sales.documents.index'),
                'action_text' => 'Ver'
            ];
        }

        return view('dashboards.sunat_dashboard', compact(
            'company',
            'salesToday',
            'salesWeek',
            'salesMonth',
            'monthChange',
            'chartLabels',
            'chartValues',
            'typeLabels',
            'typeValues',
            'typeCounts',
            'paymentLabels',
            'paymentValues',
            'recentDocuments',
            'pendingDocs',
            'rejectedDocs',
            'pendingSummaries',
            'pendingDespatch',
            'sunatConfigured',
            'topProducts',
            'topCustomers',
            'alerts'
        ));
    }

    /**
     * API para obtener datos de gráficos (AJAX)
     */
    public function chartData(Request $request)
    {
        $companyId = session('current_company_id');
        $period = $request->input('period', '30days');

        $startDate = match($period) {
            '7days' => Carbon::now()->subDays(7),
            '30days' => Carbon::now()->subDays(30),
            '90days' => Carbon::now()->subDays(90),
            'year' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(30),
        };

        $salesData = SalesDocument::where('company_id', $companyId)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('issue_date', [$startDate, Carbon::now()])
            ->selectRaw('DATE(issue_date) as date, SUM(total) as total, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'labels' => $salesData->pluck('date')->map(fn($d) => Carbon::parse($d)->format('d/m')),
            'values' => $salesData->pluck('total'),
            'counts' => $salesData->pluck('count'),
        ]);
    }
}
