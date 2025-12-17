<?php

namespace App\Http\Controllers;

use App\Models\DailySummary;
use App\Models\SalesDocument;
use App\Jobs\GenerateDailySummaryJob;
use App\Jobs\SendDailySummaryToSunatJob;
use App\Jobs\CheckDailySummaryStatusJob;
use App\Services\Sunat\SunatSummaryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DailySummaryController extends Controller
{
    /**
     * Lista de resúmenes diarios
     */
    public function index(Request $request)
    {
        $companyId = session('current_company_id');

        $query = DailySummary::where('company_id', $companyId)
            ->withCount('items')
            ->latest('summary_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('reference_date', $request->month);
        }

        $summaries = $query->paginate(15);

        // Obtener boletas pendientes de resumen del día actual
        $pendingBoletas = SalesDocument::where('company_id', $companyId)
            ->whereDate('issue_date', today())
            ->whereHas('documentType', fn($q) => $q->where('code', '03'))
            ->whereNull('daily_summary_id')
            ->where('status', 'emitted')
            ->count();

        return view('sales.summaries.index', compact('summaries', 'pendingBoletas'));
    }

    /**
     * Formulario para generar resumen
     */
    public function create(Request $request)
    {
        $companyId = session('current_company_id');
        $date = $request->get('date', today()->format('Y-m-d'));

        // Obtener boletas pendientes del día seleccionado
        $pendingBoletas = SalesDocument::where('company_id', $companyId)
            ->whereDate('issue_date', $date)
            ->whereHas('documentType', fn($q) => $q->whereIn('code', ['03']))
            ->whereNull('daily_summary_id')
            ->where('status', 'emitted')
            ->with(['series', 'customer', 'documentType'])
            ->orderBy('number')
            ->get();

        // Verificar si ya existe resumen para ese día
        $existingSummary = DailySummary::where('company_id', $companyId)
            ->whereDate('reference_date', $date)
            ->where('status', '!=', 'rejected')
            ->first();

        return view('sales.summaries.create', compact('pendingBoletas', 'date', 'existingSummary'));
    }

    /**
     * Generar resumen diario
     */
    public function store(Request $request, SunatSummaryBuilder $builder)
    {
        $companyId = session('current_company_id');

        $request->validate([
            'reference_date' => 'required|date|before_or_equal:today',
        ]);

        try {
            $date = new \DateTime($request->reference_date);

            // Verificar que hay boletas pendientes
            $pendingCount = SalesDocument::where('company_id', $companyId)
                ->whereDate('issue_date', $date)
                ->whereHas('documentType', fn($q) => $q->where('code', '03'))
                ->whereNull('daily_summary_id')
                ->where('status', 'emitted')
                ->count();

            if ($pendingCount === 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No hay boletas pendientes para la fecha seleccionada.',
                ], 422);
            }

            // Crear resumen
            $summary = $builder->createFromPendingBoletas($companyId, $date);

            if (!$summary) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo crear el resumen diario.',
                ], 500);
            }

            // Enviar a SUNAT si se solicita
            if ($request->boolean('send_to_sunat', true)) {
                SendDailySummaryToSunatJob::dispatch($summary->id);
            }

            return response()->json([
                'success' => true,
                'message' => "Resumen {$summary->identifier} generado con {$summary->total_documents} documentos.",
                'redirect' => route('sales.summaries.show', $summary->id),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle del resumen
     */
    public function show(DailySummary $summary)
    {
        $companyId = session('current_company_id');

        if ($summary->company_id != $companyId) {
            abort(403);
        }

        $summary->load(['items.salesDocument.series', 'items.salesDocument.customer', 'items.salesDocument.documentType']);

        return view('sales.summaries.show', compact('summary'));
    }

    /**
     * Reenviar a SUNAT
     */
    public function resend(DailySummary $summary)
    {
        $companyId = session('current_company_id');

        if ($summary->company_id != $companyId) {
            abort(403);
        }

        if ($summary->status === 'sent' && $summary->ticket) {
            // Si ya fue enviado, consultar estado
            CheckDailySummaryStatusJob::dispatch($summary->id);
            return back()->with('success', 'Consulta de estado encolada.');
        }

        // Reenviar
        SendDailySummaryToSunatJob::dispatch($summary->id);
        return back()->with('success', 'Resumen Diario reenviado a SUNAT.');
    }

    /**
     * Consultar estado del ticket
     */
    public function checkStatus(DailySummary $summary)
    {
        $companyId = session('current_company_id');

        if ($summary->company_id != $companyId) {
            abort(403);
        }

        if (!$summary->ticket) {
            return back()->with('error', 'No hay ticket para consultar.');
        }

        CheckDailySummaryStatusJob::dispatch($summary->id);
        return back()->with('success', 'Consulta de estado encolada.');
    }

    /**
     * Generar resumen automático para el día anterior (para scheduler)
     */
    public function generateDaily(Request $request)
    {
        $companyId = session('current_company_id');
        $date = $request->get('date', now()->subDay()->format('Y-m-d'));

        GenerateDailySummaryJob::dispatch($companyId, $date);

        return back()->with('success', "Generación de resumen para {$date} encolada.");
    }
}
