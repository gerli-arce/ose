<?php

namespace App\Http\Controllers;

use App\Models\SalesDocument;
use App\Models\VoidedDocument;
use App\Models\VoidedDocumentItem;
use App\Jobs\SendVoidedDocumentToSunatJob;
use App\Jobs\CheckVoidedDocumentStatusJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoidedDocumentController extends Controller
{
    /**
     * Lista de comunicaciones de baja
     */
    public function index(Request $request)
    {
        $companyId = session('current_company_id');

        $query = VoidedDocument::where('company_id', $companyId)
            ->with(['items.salesDocument.series', 'items.salesDocument.documentType'])
            ->latest('voided_date');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $voidedDocuments = $query->paginate(15);

        return view('sales.voided.index', compact('voidedDocuments'));
    }

    /**
     * Mostrar formulario para anular un documento
     */
    public function create(Request $request)
    {
        $companyId = session('current_company_id');
        $documentId = $request->get('document_id');

        if (!$documentId) {
            return redirect()->route('sales.documents.index')
                ->with('error', 'Debe seleccionar un documento para anular.');
        }

        $document = SalesDocument::where('company_id', $companyId)
            ->with(['series', 'documentType', 'customer'])
            ->findOrFail($documentId);

        // Validar que se puede anular
        if (!$this->canVoid($document)) {
            return back()->with('error', $this->getVoidError($document));
        }

        return view('sales.voided.create', compact('document'));
    }

    /**
     * Crear comunicación de baja
     */
    public function store(Request $request)
    {
        $companyId = session('current_company_id');

        $request->validate([
            'document_id' => 'required|exists:sales_documents,id',
            'reason' => 'required|string|max:500',
        ]);

        try {
            $document = SalesDocument::where('company_id', $companyId)
                ->with(['series', 'documentType'])
                ->findOrFail($request->document_id);

            // Validar nuevamente
            if (!$this->canVoid($document)) {
                return response()->json([
                    'success' => false,
                    'message' => $this->getVoidError($document),
                ], 422);
            }

            $voidedDocument = DB::transaction(function () use ($companyId, $document, $request) {
                $today = now();

                // Crear comunicación de baja
                $identifier = VoidedDocument::generateIdentifier($companyId, $today);
                
                $voidedDoc = VoidedDocument::create([
                    'company_id' => $companyId,
                    'identifier' => $identifier,
                    'voided_date' => $today,
                    'reference_date' => $document->issue_date,
                    'status' => 'pending',
                ]);

                // Crear item
                VoidedDocumentItem::create([
                    'voided_document_id' => $voidedDoc->id,
                    'sales_document_id' => $document->id,
                    'document_type_code' => $document->documentType->code,
                    'series' => $document->series->prefix,
                    'number' => $document->number,
                    'reason' => $request->reason,
                ]);

                return $voidedDoc;
            });

            // Enviar a SUNAT
            if ($request->boolean('send_to_sunat', true)) {
                SendVoidedDocumentToSunatJob::dispatch($voidedDocument->id);
            }

            return response()->json([
                'success' => true,
                'message' => 'Comunicación de Baja creada. Se enviará a SUNAT.',
                'redirect' => route('sales.voided.show', $voidedDocument->id),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle de comunicación de baja
     */
    public function show(VoidedDocument $voided)
    {
        $companyId = session('current_company_id');

        if ($voided->company_id != $companyId) {
            abort(403);
        }

        $voided->load(['items.salesDocument.series', 'items.salesDocument.documentType', 'items.salesDocument.customer']);

        return view('sales.voided.show', compact('voided'));
    }

    /**
     * Reenviar a SUNAT
     */
    public function resend(VoidedDocument $voided)
    {
        $companyId = session('current_company_id');

        if ($voided->company_id != $companyId) {
            abort(403);
        }

        if ($voided->status === 'sent' && $voided->ticket) {
            // Si ya fue enviado, consultar estado
            CheckVoidedDocumentStatusJob::dispatch($voided->id);
            return back()->with('success', 'Consulta de estado encolada.');
        }

        // Reenviar
        SendVoidedDocumentToSunatJob::dispatch($voided->id);
        return back()->with('success', 'Comunicación de Baja reenviada a SUNAT.');
    }

    /**
     * Consultar estado del ticket
     */
    public function checkStatus(VoidedDocument $voided)
    {
        $companyId = session('current_company_id');

        if ($voided->company_id != $companyId) {
            abort(403);
        }

        if (!$voided->ticket) {
            return back()->with('error', 'No hay ticket para consultar.');
        }

        CheckVoidedDocumentStatusJob::dispatch($voided->id);
        return back()->with('success', 'Consulta de estado encolada.');
    }

    /**
     * Verificar si un documento puede ser anulado
     */
    private function canVoid(SalesDocument $document): bool
    {
        // Solo facturas (01) por ahora - las boletas van por Resumen Diario
        if (!in_array($document->documentType?->code, ['01', '07', '08'])) {
            return false;
        }

        // Debe estar emitido (no borrador, no ya anulado)
        if ($document->status !== 'emitted') {
            return false;
        }

        // Debe estar aceptado por SUNAT o al menos enviado
        if (!in_array($document->sunat_status, ['accepted', 'pending', 'sent'])) {
            return false;
        }

        // No debe tener ya una comunicación de baja aceptada
        if ($document->voided_document_id) {
            return false;
        }

        return true;
    }

    /**
     * Obtener mensaje de error de anulación
     */
    private function getVoidError(SalesDocument $document): string
    {
        if ($document->documentType?->code === '03') {
            return 'Las boletas de venta se anulan mediante Resumen Diario, no por Comunicación de Baja.';
        }

        if ($document->status !== 'emitted') {
            return 'Solo se pueden anular documentos emitidos.';
        }

        if ($document->voided_document_id) {
            return 'Este documento ya tiene una comunicación de baja asociada.';
        }

        if (!in_array($document->sunat_status, ['accepted', 'pending', 'sent'])) {
            return 'Solo se pueden anular documentos que hayan sido procesados por SUNAT.';
        }

        return 'No se puede anular este documento.';
    }
}
