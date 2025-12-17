<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Currency;
use App\Models\DocumentSeries;
use App\Models\DocumentType;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationItem;
use App\Models\SalesDocument;
use App\Models\SalesDocumentItem;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuotationController extends Controller
{
    /**
     * Listado de cotizaciones
     */
    public function index(Request $request)
    {
        $companyId = session('current_company_id');

        $query = Quotation::forCompany($companyId)
            ->with(['customer', 'currency', 'seller'])
            ->orderByDesc('issue_date')
            ->orderByDesc('number');

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('issue_date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('issue_date', '<=', $request->to_date);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($c) => $c->where('name', 'like', "%{$search}%"));
            });
        }

        $quotations = $query->paginate(20)->withQueryString();

        $statuses = Quotation::getStatuses();
        $customers = Contact::where('company_id', $companyId)
            ->where('is_customer', true)
            ->orderBy('name')
            ->get();

        return view('quotations.index', compact('quotations', 'statuses', 'customers'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $companyId = session('current_company_id');

        $customers = Contact::where('company_id', $companyId)
            ->where('is_customer', true)
            ->orderBy('name')
            ->get();

        $products = Product::where('company_id', $companyId)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $currencies = Currency::where('active', true)->get();

        $nextNumber = Quotation::getNextNumber($companyId);

        return view('quotations.create', compact('customers', 'products', 'currencies', 'nextNumber'));
    }

    /**
     * Guardar cotización
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:contacts,id',
            'currency_id' => 'required|exists:currencies,id',
            'issue_date' => 'required|date',
            'validity_days' => 'required|integer|min:1|max:365',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        DB::beginTransaction();

        try {
            $issueDate = Carbon::parse($validated['issue_date']);
            $expiryDate = $issueDate->copy()->addDays($validated['validity_days']);

            // Crear cotización
            $quotation = Quotation::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'customer_id' => $validated['customer_id'],
                'currency_id' => $validated['currency_id'],
                'series' => 'COT',
                'number' => Quotation::getNextNumber($companyId),
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate,
                'seller_id' => auth()->id(),
                'validity_days' => $validated['validity_days'],
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
                'status' => Quotation::STATUS_DRAFT,
            ]);

            // Crear items
            $taxRate = 0.18; // IGV Perú
            foreach ($validated['items'] as $index => $itemData) {
                $quantity = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $discountPercent = floatval($itemData['discount_percent'] ?? 0);

                $grossAmount = $quantity * $unitPrice;
                $discountAmount = $grossAmount * ($discountPercent / 100);
                $subtotal = $grossAmount - $discountAmount;
                $taxAmount = $subtotal * $taxRate;
                $total = $subtotal + $taxAmount;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $itemData['product_id'] ?? null,
                    'description' => $itemData['description'],
                    'unit_code' => 'NIU',
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'sort_order' => $index,
                ]);
            }

            // Recalcular totales
            $quotation->recalculateTotals();

            DB::commit();

            return redirect()
                ->route('quotations.show', $quotation->id)
                ->with('success', "Cotización {$quotation->full_number} creada exitosamente.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear cotización: ' . $e->getMessage());
            return back()->with('error', 'Error al crear la cotización: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Ver detalle de cotización
     */
    public function show(Quotation $quotation)
    {
        $this->authorizeCompany($quotation);

        $quotation->load(['customer', 'currency', 'seller', 'items.product', 'salesDocument']);

        return view('quotations.show', compact('quotation'));
    }

    /**
     * Formulario de edición
     */
    public function edit(Quotation $quotation)
    {
        $this->authorizeCompany($quotation);

        if (!$quotation->canEdit()) {
            return back()->with('error', 'Esta cotización no puede ser editada.');
        }

        $companyId = session('current_company_id');

        $customers = Contact::where('company_id', $companyId)
            ->where('is_customer', true)
            ->orderBy('name')
            ->get();

        $products = Product::where('company_id', $companyId)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $currencies = Currency::where('active', true)->get();

        $quotation->load(['items.product']);

        return view('quotations.edit', compact('quotation', 'customers', 'products', 'currencies'));
    }

    /**
     * Actualizar cotización
     */
    public function update(Request $request, Quotation $quotation)
    {
        $this->authorizeCompany($quotation);

        if (!$quotation->canEdit()) {
            return back()->with('error', 'Esta cotización no puede ser editada.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:contacts,id',
            'currency_id' => 'required|exists:currencies,id',
            'issue_date' => 'required|date',
            'validity_days' => 'required|integer|min:1|max:365',
            'notes' => 'nullable|string|max:1000',
            'terms' => 'nullable|string|max:2000',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'nullable|exists:products,id',
            'items.*.description' => 'required|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.0001',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        DB::beginTransaction();

        try {
            $issueDate = Carbon::parse($validated['issue_date']);
            $expiryDate = $issueDate->copy()->addDays($validated['validity_days']);

            // Actualizar cotización
            $quotation->update([
                'customer_id' => $validated['customer_id'],
                'currency_id' => $validated['currency_id'],
                'issue_date' => $issueDate,
                'expiry_date' => $expiryDate,
                'validity_days' => $validated['validity_days'],
                'notes' => $validated['notes'] ?? null,
                'terms' => $validated['terms'] ?? null,
            ]);

            // Eliminar items anteriores y crear nuevos
            $quotation->items()->delete();

            $taxRate = 0.18;
            foreach ($validated['items'] as $index => $itemData) {
                $quantity = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $discountPercent = floatval($itemData['discount_percent'] ?? 0);

                $grossAmount = $quantity * $unitPrice;
                $discountAmount = $grossAmount * ($discountPercent / 100);
                $subtotal = $grossAmount - $discountAmount;
                $taxAmount = $subtotal * $taxRate;
                $total = $subtotal + $taxAmount;

                QuotationItem::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $itemData['product_id'] ?? null,
                    'description' => $itemData['description'],
                    'unit_code' => 'NIU',
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'discount_percent' => $discountPercent,
                    'discount_amount' => $discountAmount,
                    'subtotal' => $subtotal,
                    'tax_amount' => $taxAmount,
                    'total' => $total,
                    'sort_order' => $index,
                ]);
            }

            $quotation->recalculateTotals();

            DB::commit();

            return redirect()
                ->route('quotations.show', $quotation->id)
                ->with('success', 'Cotización actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar cotización: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar la cotización.')->withInput();
        }
    }

    /**
     * Eliminar cotización
     */
    public function destroy(Quotation $quotation)
    {
        $this->authorizeCompany($quotation);

        if ($quotation->status === Quotation::STATUS_INVOICED) {
            return back()->with('error', 'No se puede eliminar una cotización facturada.');
        }

        $quotation->delete();

        return redirect()
            ->route('quotations.index')
            ->with('success', 'Cotización eliminada exitosamente.');
    }

    /**
     * Convertir a factura
     */
    public function convertToInvoice(Request $request, Quotation $quotation)
    {
        $this->authorizeCompany($quotation);

        if (!$quotation->canConvertToInvoice()) {
            return back()->with('error', 'Esta cotización no puede ser convertida a factura.');
        }

        $validated = $request->validate([
            'document_type' => 'required|in:01,03',
            'series_id' => 'required|exists:document_series,id',
        ]);

        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        DB::beginTransaction();

        try {
            $quotation->load(['customer', 'items']);

            // Obtener serie y tipo de documento
            $series = DocumentSeries::findOrFail($validated['series_id']);
            $documentType = DocumentType::where('code', $validated['document_type'])->first();
            $nextNumber = $series->current_number + 1;

            // Verificar stock si es necesario
            foreach ($quotation->items as $item) {
                if ($item->product_id) {
                    $stock = Stock::where('product_id', $item->product_id)
                        ->where('warehouse_id', $series->warehouse_id ?? 1)
                        ->first();

                    if ($stock && $stock->quantity < $item->quantity) {
                        throw new \Exception("Stock insuficiente para {$item->description}. Disponible: {$stock->quantity}");
                    }
                }
            }

            // Crear documento de venta
            $salesDocument = SalesDocument::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'document_type_id' => $documentType->id,
                'series_id' => $series->id,
                'number' => $nextNumber,
                'customer_id' => $quotation->customer_id,
                'currency_id' => $quotation->currency_id,
                'issue_date' => Carbon::today(),
                'due_date' => Carbon::today()->addDays(30),
                'subtotal' => $quotation->subtotal,
                'discount_total' => $quotation->discount_total,
                'tax_total' => $quotation->tax_total,
                'total' => $quotation->total,
                'exchange_rate' => $quotation->exchange_rate,
                'status' => 'emitted',
                'payment_status' => 'unpaid',
                'notes' => "Generado desde Cotización {$quotation->full_number}",
            ]);

            // Crear items del documento
            foreach ($quotation->items as $item) {
                SalesDocumentItem::create([
                    'sales_document_id' => $salesDocument->id,
                    'product_id' => $item->product_id,
                    'description' => $item->description,
                    'unit_code' => $item->unit_code,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'discount' => $item->discount_amount,
                    'subtotal' => $item->subtotal,
                    'tax_amount' => $item->tax_amount,
                    'line_total' => $item->total,
                ]);
            }

            // Actualizar número de serie
            $series->update(['current_number' => $nextNumber]);

            // Marcar cotización como facturada
            $quotation->markAsInvoiced($salesDocument->id);

            DB::commit();

            return redirect()
                ->route('sales.documents.show', $salesDocument->id)
                ->with('success', "Factura/Boleta {$series->prefix}-" . str_pad($nextNumber, 8, '0', STR_PAD_LEFT) . " creada desde cotización.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al convertir cotización: ' . $e->getMessage());
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Cambiar estado
     */
    public function changeStatus(Request $request, Quotation $quotation)
    {
        $this->authorizeCompany($quotation);

        $validated = $request->validate([
            'status' => 'required|in:accepted,rejected',
        ]);

        if ($validated['status'] === 'accepted') {
            $quotation->markAsAccepted();
            $message = 'Cotización marcada como aceptada.';
        } else {
            $quotation->markAsRejected();
            $message = 'Cotización marcada como rechazada.';
        }

        return back()->with('success', $message);
    }

    /**
     * Duplicar cotización
     */
    public function duplicate(Quotation $quotation)
    {
        $this->authorizeCompany($quotation);

        $companyId = session('current_company_id');

        DB::beginTransaction();

        try {
            $newQuotation = $quotation->replicate();
            $newQuotation->number = Quotation::getNextNumber($companyId);
            $newQuotation->issue_date = Carbon::today();
            $newQuotation->expiry_date = Carbon::today()->addDays($quotation->validity_days);
            $newQuotation->status = Quotation::STATUS_DRAFT;
            $newQuotation->sales_document_id = null;
            $newQuotation->save();

            foreach ($quotation->items as $item) {
                $newItem = $item->replicate();
                $newItem->quotation_id = $newQuotation->id;
                $newItem->save();
            }

            DB::commit();

            return redirect()
                ->route('quotations.edit', $newQuotation->id)
                ->with('success', "Cotización duplicada como {$newQuotation->full_number}.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al duplicar la cotización.');
        }
    }

    /**
     * Autorizar acceso por empresa
     */
    private function authorizeCompany(Quotation $quotation): void
    {
        $companyId = session('current_company_id');
        
        if ($quotation->company_id !== $companyId) {
            abort(403, 'No autorizado');
        }
    }
}
