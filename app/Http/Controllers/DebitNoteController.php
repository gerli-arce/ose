<?php

namespace App\Http\Controllers;

use App\Models\DebitNoteType;
use App\Models\DocumentSeries;
use App\Models\DocumentType;
use App\Models\EDocument;
use App\Models\SalesDocument;
use App\Models\SalesDocumentItem;
use App\Jobs\SendDebitNoteToSunatJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DebitNoteController extends Controller
{
    /**
     * Lista de notas de débito emitidas
     */
    public function index(Request $request)
    {
        $companyId = session('current_company_id');

        // Obtener tipo de documento ND (08)
        $ndDocType = DocumentType::where('code', '08')->first();

        $query = SalesDocument::where('company_id', $companyId)
            ->where('document_type_id', $ndDocType?->id)
            ->with(['series', 'customer', 'relatedDocument.series', 'debitNoteType'])
            ->latest('issue_date');

        if ($request->filled('status')) {
            $query->where('sunat_status', $request->status);
        }

        if ($request->filled('month')) {
            $query->whereMonth('issue_date', $request->month);
        }

        $debitNotes = $query->paginate(15);

        return view('sales.debit-notes.index', compact('debitNotes'));
    }

    /**
     * Formulario de creación de ND
     */
    public function create(Request $request)
    {
        $companyId = session('current_company_id');
        $documentId = $request->get('document_id');

        if (!$documentId) {
            return redirect()->route('sales.documents.index')
                ->with('error', 'Debe seleccionar un documento para aplicar cargo adicional.');
        }

        // Cargar documento original
        $originalDocument = SalesDocument::where('company_id', $companyId)
            ->with(['series', 'documentType', 'customer', 'items.product'])
            ->findOrFail($documentId);

        // Validar que es factura o boleta
        if (!in_array($originalDocument->documentType?->code, ['01', '03'])) {
            return back()->with('error', 'Solo se pueden emitir notas de débito para facturas o boletas.');
        }

        // Validar que está emitido
        if ($originalDocument->status !== 'emitted') {
            return back()->with('error', 'Solo se pueden emitir notas de débito para documentos emitidos.');
        }

        // Tipos de nota de débito activos
        $debitNoteTypes = DebitNoteType::active()->get();

        return view('sales.debit-notes.create', compact('originalDocument', 'debitNoteTypes'));
    }

    /**
     * Guardar nota de débito
     */
    public function store(Request $request)
    {
        $companyId = session('current_company_id');

        $request->validate([
            'related_document_id' => 'required|exists:sales_documents,id',
            'debit_note_type_id' => 'required|exists:debit_note_types,id',
            'note_reason' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0.01',
        ]);

        try {
            $originalDoc = SalesDocument::where('company_id', $companyId)
                ->with(['series', 'documentType'])
                ->findOrFail($request->related_document_id);

            // Determinar serie de ND
            $ndDocType = DocumentType::where('code', '08')->firstOrFail();
            
            // La serie de ND depende del documento original
            $seriesPrefix = $originalDoc->documentType->code === '01' ? 'FD01' : 'BD01';
            $series = DocumentSeries::firstOrCreate(
                ['company_id' => $companyId, 'prefix' => $seriesPrefix],
                ['document_type_id' => $ndDocType->id, 'current_number' => 0]
            );

            $debitNote = DB::transaction(function () use ($request, $companyId, $originalDoc, $ndDocType, $series) {
                // Incrementar número
                $series->increment('current_number');
                $number = $series->current_number;

                // Calcular totales
                $subtotal = 0;
                foreach ($request->items as $item) {
                    $subtotal += $item['quantity'] * $item['unit_price'];
                }
                $igv = round($subtotal * 0.18, 2);
                $total = round($subtotal + $igv, 2);

                // Crear nota de débito
                $nd = SalesDocument::create([
                    'company_id' => $companyId,
                    'customer_id' => $originalDoc->customer_id,
                    'document_type_id' => $ndDocType->id,
                    'series_id' => $series->id,
                    'number' => $number,
                    'issue_date' => now(),
                    'due_date' => now()->addDays(30),
                    'currency' => $originalDoc->currency ?? 'PEN',
                    'exchange_rate' => 1,
                    'subtotal' => $subtotal,
                    'tax_total' => $igv,
                    'total' => $total,
                    'status' => 'emitted',
                    'payment_status' => 'unpaid',
                    'sunat_status' => 'pending',
                    'related_document_id' => $originalDoc->id,
                    'debit_note_type_id' => $request->debit_note_type_id,
                    'note_reason' => $request->note_reason,
                ]);

                // Crear items
                foreach ($request->items as $itemData) {
                    $itemTotal = $itemData['quantity'] * $itemData['unit_price'];
                    
                    SalesDocumentItem::create([
                        'sales_document_id' => $nd->id,
                        'product_id' => null,
                        'description' => $itemData['description'],
                        'quantity' => $itemData['quantity'],
                        'unit_price' => $itemData['unit_price'],
                        'discount' => 0,
                        'tax' => round($itemTotal * 0.18, 2),
                        'total' => $itemTotal,
                    ]);
                }

                // Crear registro de documento electrónico
                EDocument::create([
                    'sales_document_id' => $nd->id,
                    'response_status' => 'pending',
                ]);

                return $nd;
            });

            // Enviar a SUNAT si se solicita
            if ($request->boolean('send_to_sunat', true)) {
                SendDebitNoteToSunatJob::dispatch($debitNote->id);
            }

            return response()->json([
                'success' => true,
                'message' => "Nota de Débito {$series->prefix}-" . str_pad($debitNote->number, 8, '0', STR_PAD_LEFT) . " creada.",
                'redirect' => route('sales.debit-notes.show', $debitNote->id),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Ver detalle de nota de débito
     */
    public function show(SalesDocument $debitNote)
    {
        $companyId = session('current_company_id');

        if ($debitNote->company_id != $companyId) {
            abort(403);
        }

        $debitNote->load([
            'series',
            'documentType',
            'customer',
            'items',
            'relatedDocument.series',
            'relatedDocument.documentType',
            'debitNoteType',
            'eDocument'
        ]);

        return view('sales.debit-notes.show', compact('debitNote'));
    }

    /**
     * Reenviar a SUNAT
     */
    public function resendToSunat(SalesDocument $debitNote)
    {
        $companyId = session('current_company_id');

        if ($debitNote->company_id != $companyId) {
            abort(403);
        }

        SendDebitNoteToSunatJob::dispatch($debitNote->id);

        return back()->with('success', 'Nota de Débito reenviada a SUNAT.');
    }
}
