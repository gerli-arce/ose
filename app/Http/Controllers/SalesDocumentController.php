<?php

namespace App\Http\Controllers;

use App\Models\SalesDocument;
use App\Models\SalesDocumentItem;
use App\Models\SalesPayment;
use App\Models\Product;
use App\Models\Contact;
use App\Models\DocumentType;
use App\Models\DocumentSeries;
use App\Models\StockMovement;
use App\Models\Stock;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class SalesDocumentController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('current_company_id');
        
        $query = SalesDocument::where('company_id', $companyId)
            ->with(['customer', 'documentType', 'eDocument']);

        if ($request->filled('start_date')) {
            $query->whereDate('issue_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('issue_date', '<=', $request->end_date);
        }
        if ($request->filled('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }
        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }
        if ($request->filled('status')) {
             $query->where('status', $request->status);
        }

        $documents = $query->latest('issue_date')->latest('number')->paginate(15);
        $documentTypes = DocumentType::all(); // Assuming global or filtered by active
        
        return view('sales.invoices.index', compact('documents', 'documentTypes'));
    }

    public function create()
    {
        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        $documentTypes = DocumentType::whereIn('code', ['01', '03'])->get(); // Factura, Boleta
        $series = DocumentSeries::where('branch_id', $branchId)->get();
        
        // Data for JS
        $paymentMethods = PaymentMethod::all(); // Assuming seeded
        
        return view('sales.invoices.create', compact('documentTypes', 'series', 'paymentMethods'));
    }

    public function store(Request $request)
    {
        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        // Validation
        $request->validate([
            'document_type_id' => 'required',
            'series' => 'required',
            'customer_id' => 'required|exists:contacts,id',
            'issue_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric',
            'items.*.total' => 'required|numeric',
        ]);

        try {
            DB::beginTransaction();

            // 1. Get and Increment Number
            $docSeries = DocumentSeries::where('branch_id', $branchId)
                ->where('document_type_id', $request->document_type_id)
                ->where('prefix', $request->series) // The input name is 'series' but matches 'prefix' column
                ->firstOrFail();
            
            $number = $docSeries->current_number + 1;
            $docSeries->current_number = $number;
            $docSeries->save();

            // 2. Create Header
            $currency = \App\Models\Currency::where('code', 'PEN')->firstOrFail();
            
            $document = SalesDocument::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'customer_id' => $request->customer_id,
                'document_type_id' => $request->document_type_id,
                'series_id' => $docSeries->id, // Use ID
                'number' => $number,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date ?? $request->issue_date,
                'currency_id' => $currency->id, // Use ID
                'observation' => $request->observation,
                'subtotal' => $request->subtotal,
                'tax_total' => $request->total_igv, // Map input to DB column
                'total_discount' => 0, 
                'total' => $request->total,
                'status' => 'emitted',
                'sunat_status' => 'pending',
                'payment_status' => 'pending' 
            ]);

            // 3. Create Items & Deduct Stock
            foreach ($request->items as $item) {
                // Save Item
                SalesDocumentItem::create([
                    'sales_document_id' => $document->id,
                    'product_id' => $item['product_id'],
                    'code' => $item['code'], // Hidden field or lookup
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total' => $item['total'],
                    'igv_amount' => $item['igv'] ?? 0,
                    'discount_amount' => 0
                ]);

                // Stock Deduction (If Product not Service)
                $product = Product::find($item['product_id']);
                if (!$product->is_service) {
                    $this->deductStock($companyId, $branchId, $product, $item['quantity'], $document);
                }
            }

            // 4. E-Invoice Simulation (Create EDocument entry)
            $document->eDocument()->create([
                'company_id' => $companyId,
                'response_status' => 'pending',
                'sent_at' => null
            ]);
            
            // 5. Simulate immediate "Send" if requested (or queued)
            if ($request->has('send_to_sunat')) {
                // In generic mode, we might just mark it accepted for demo
                // Or leave separate action. Let's just create the record pending.
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'redirect' => route('sales.documents.show', $document->id),
                'message' => 'Documento emitido correctamente.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
             return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    public function show(SalesDocument $document)
    {
         $companyId = session('current_company_id');
         if ($document->company_id != $companyId) abort(403);
         
         $document->load('items', 'items.product', 'customer', 'eDocument', 'payments');
         
         return view('sales.invoices.show', compact('document'));
    }

    // Helper for Stock Deduction
    private function deductStock($companyId, $branchId, $product, $qty, $document)
    {
        // Try to find a default warehouse for the branch. 
        // In real app, warehouse should be selected in form. 
        // For simple demo, pick first active warehouse of branch.
        $warehouse = \App\Models\Warehouse::where('branch_id', $branchId)->first();
        
        if (!$warehouse) return; // Cannot deduct if no warehouse

        // 1. Create Stock Movement
        StockMovement::create([
            'company_id' => $companyId,
            'branch_id' => $branchId,
            'warehouse_id' => $warehouse->id,
            'product_id' => $product->id,
            'date' => now(), // Or document date
            'type' => 'out',
            'quantity' => $qty,
            'source_type' => 'sale', // document
            // 'source_id' => $document->id, // need column in stock_movement or polymorphism
            'cost_unit' => $product->cost_price, 
            'observations' => 'Venta ' . $document->series . '-' . $document->number
        ]);

        // 2. Update Stock
        $stock = Stock::firstOrNew([
            'product_id' => $product->id,
            'warehouse_id' => $warehouse->id
        ]);
        
        if (!$stock->exists) $stock->quantity = 0;
        $stock->quantity -= $qty;
        $stock->save();
    }
}
