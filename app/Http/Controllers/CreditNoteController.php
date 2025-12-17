<?php

namespace App\Http\Controllers;

use App\Models\SalesDocument;
use App\Models\SalesDocumentItem;
use App\Models\DocumentType;
use App\Models\DocumentSeries;
use App\Models\CreditNoteType;
use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Jobs\SendCreditNoteToSunatJob;

class CreditNoteController extends Controller
{
    /**
     * Lista de notas de crédito emitidas
     */
    public function index(Request $request)
    {
        $companyId = session('current_company_id');
        
        $documentType = DocumentType::where('code', '07')->first();
        
        $query = SalesDocument::where('company_id', $companyId)
            ->where('document_type_id', $documentType?->id)
            ->with(['customer', 'relatedDocument.series', 'creditNoteType', 'eDocument']);

        if ($request->filled('start_date')) {
            $query->whereDate('issue_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('issue_date', '<=', $request->end_date);
        }

        $documents = $query->latest('issue_date')->latest('number')->paginate(15);
        
        return view('sales.credit-notes.index', compact('documents'));
    }

    /**
     * Formulario para crear NC desde un documento
     */
    public function create(Request $request)
    {
        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        // Documento origen requerido
        $relatedDocumentId = $request->get('document_id');
        
        if (!$relatedDocumentId) {
            return redirect()->route('sales.documents.index')
                ->with('error', 'Debe seleccionar un documento para emitir la Nota de Crédito.');
        }

        $relatedDocument = SalesDocument::where('company_id', $companyId)
            ->with(['items.product', 'customer', 'series', 'documentType'])
            ->findOrFail($relatedDocumentId);

        // Verificar que se puede emitir NC
        if (!$relatedDocument->canIssueCreditNote()) {
            return redirect()->route('sales.documents.show', $relatedDocument->id)
                ->with('error', 'No se puede emitir Nota de Crédito para este documento.');
        }

        // Obtener tipos de NC
        $creditNoteTypes = CreditNoteType::active()->get();

        // Series para NC (código 07)
        $documentType = DocumentType::where('code', '07')->first();
        $series = DocumentSeries::where('branch_id', $branchId)
            ->where('document_type_id', $documentType?->id)
            ->get();

        // Si no hay series, crear una por defecto
        if ($series->isEmpty() && $documentType) {
            $prefix = $relatedDocument->isFactura() ? 'FC01' : 'BC01';
            $series = collect([
                DocumentSeries::create([
                    'branch_id' => $branchId,
                    'document_type_id' => $documentType->id,
                    'prefix' => $prefix,
                    'current_number' => 0,
                    'active' => true,
                ])
            ]);
        }

        return view('sales.credit-notes.create', compact(
            'relatedDocument', 
            'creditNoteTypes', 
            'series',
            'documentType'
        ));
    }

    /**
     * Almacenar NC
     */
    public function store(Request $request)
    {
        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        $request->validate([
            'related_document_id' => 'required|exists:sales_documents,id',
            'credit_note_type_id' => 'required|exists:credit_note_types,id',
            'series_id' => 'required|exists:document_series,id',
            'issue_date' => 'required|date',
            'note_reason' => 'required|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // Obtener documento relacionado
            $relatedDocument = SalesDocument::where('company_id', $companyId)
                ->findOrFail($request->related_document_id);

            // Obtener tipo de documento NC
            $documentType = DocumentType::where('code', '07')->firstOrFail();

            // Obtener serie y nuevo número
            $docSeries = DocumentSeries::findOrFail($request->series_id);
            $number = $docSeries->current_number + 1;
            $docSeries->current_number = $number;
            $docSeries->save();

            // Obtener tipo de NC
            $creditNoteType = CreditNoteType::findOrFail($request->credit_note_type_id);

            // Crear NC
            $creditNote = SalesDocument::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'customer_id' => $relatedDocument->customer_id,
                'document_type_id' => $documentType->id,
                'series_id' => $docSeries->id,
                'number' => $number,
                'issue_date' => $request->issue_date,
                'due_date' => $request->issue_date,
                'currency_id' => $relatedDocument->currency_id,
                'exchange_rate' => $relatedDocument->exchange_rate,
                'subtotal' => $request->subtotal,
                'tax_total' => $request->total_igv,
                'total_discount' => 0,
                'total' => $request->total,
                'status' => 'emitted',
                'sunat_status' => 'pending',
                'payment_status' => 'paid', // NC no tiene estado de pago
                // Campos NC
                'related_document_id' => $relatedDocument->id,
                'credit_note_type_id' => $creditNoteType->id,
                'note_reason' => $request->note_reason,
                'observation' => $request->observation,
            ]);

            // Crear items
            foreach ($request->items as $item) {
                SalesDocumentItem::create([
                    'sales_document_id' => $creditNote->id,
                    'product_id' => $item['product_id'],
                    'code' => $item['code'],
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                    'igv_amount' => $item['igv'] ?? 0,
                    'discount_amount' => 0,
                ]);

                // Ajustar stock si corresponde
                if ($creditNoteType->affects_stock) {
                    $this->adjustStock($companyId, $branchId, $item, $creditNote);
                }
            }

            // Crear registro EDocument
            $creditNote->eDocument()->create([
                'company_id' => $companyId,
                'response_status' => 'pending',
                'sent_at' => null,
            ]);

            // Enviar a SUNAT si se solicita
            if ($request->boolean('send_to_sunat')) {
                SendCreditNoteToSunatJob::dispatch($creditNote->id);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('sales.credit-notes.show', $creditNote->id),
                'message' => 'Nota de Crédito emitida correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostrar NC
     */
    public function show(SalesDocument $creditNote)
    {
        $companyId = session('current_company_id');
        
        if ($creditNote->company_id != $companyId) {
            abort(403);
        }

        $creditNote->load([
            'items.product', 
            'customer', 
            'relatedDocument.series',
            'relatedDocument.documentType',
            'creditNoteType',
            'eDocument',
            'series'
        ]);

        return view('sales.credit-notes.show', compact('creditNote'));
    }

    /**
     * Ajustar stock (entrada por devolución)
     */
    private function adjustStock($companyId, $branchId, $item, $creditNote)
    {
        $product = \App\Models\Product::find($item['product_id']);
        
        if (!$product || $product->is_service) {
            return;
        }

        $warehouse = \App\Models\Warehouse::where('branch_id', $branchId)->first();
        
        if (!$warehouse) {
            return;
        }

        // Movimiento de entrada (devolución)
        StockMovement::create([
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'date' => now(),
            'type' => 'in', // Entrada por devolución
            'quantity' => $item['quantity'],
            'source_type' => 'credit_note',
            'cost_unit' => $product->cost_price,
            'observations' => 'Devolución NC ' . $creditNote->full_number,
        ]);

        // Actualizar stock
        $stock = Stock::firstOrNew([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id,
        ]);
        
        if (!$stock->exists) {
            $stock->quantity = 0;
        }
        
        $stock->quantity += $item['quantity'];
        $stock->save();
    }

    /**
     * Reenviar a SUNAT
     */
    public function resendToSunat(SalesDocument $creditNote)
    {
        $companyId = session('current_company_id');
        
        if ($creditNote->company_id != $companyId) {
            abort(403);
        }

        SendCreditNoteToSunatJob::dispatch($creditNote->id);

        return back()->with('success', 'Envío a SUNAT encolado.');
    }
}
