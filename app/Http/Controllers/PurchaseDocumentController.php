<?php

namespace App\Http\Controllers;

use App\Models\PurchaseDocument;
use App\Models\PurchaseDocumentItem;
use App\Models\DocumentType;
use App\Models\Branch;
use App\Models\StockMovement;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Contact;
use App\Models\Warehouse;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseDocumentController extends Controller
{
    public function index(Request $request)
    {
        $currentCompany = session('current_company_id');
        if (!$currentCompany) {
            return redirect()->route('dashboard')->with('error', 'No empresa asignada.');
        }

        $query = PurchaseDocument::where('company_id', $currentCompany)
            ->with(['supplier', 'documentType', 'currency', 'payments']);

        // Filters
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('issue_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('issue_date', '<=', $request->end_date);
        }
        if ($request->has('supplier_id') && $request->supplier_id) {
            $query->where('supplier_id', $request->supplier_id);
        }

        $purchases = $query->latest('issue_date')->paginate(15);
        $suppliers = Contact::where('company_id', $currentCompany)->whereIn('type', ['supplier', 'both'])->get();

        return view('purchases.documents.index', compact('purchases', 'suppliers'));
    }

    public function create()
    {
        $currentCompany = session('current_company_id');
        
        $documentTypes = DocumentType::all(); // Filter if needed
        $suppliers = Contact::where('company_id', $currentCompany)->whereIn('type', ['supplier', 'both'])->get();
        $currencies = Currency::all();
        $branches = Branch::where('company_id', $currentCompany)->get();
        $warehouses = Warehouse::where('company_id', $currentCompany)->get();

        return view('purchases.documents.create', compact('documentTypes', 'suppliers', 'currencies', 'branches', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:contacts,id',
            'document_type_id' => 'required|exists:document_types,id',
            'series' => 'required|string|max:10',
            'number' => 'required|string|max:20',
            'issue_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'warehouse_id' => 'required_if:update_stock,1',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $currentCompany = session('current_company_id');
            // Allow manual branch selection or default to current
            $currentBranch = session('current_branch_id'); 

            // 1. Create Purchase Header
            $purchase = PurchaseDocument::create([
                'company_id' => $currentCompany,
                'branch_id' => $currentBranch,
                'supplier_id' => $request->supplier_id,
                'document_type_id' => $request->document_type_id,
                'series' => strtoupper($request->series),
                'number' => $request->number,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date ?? $request->issue_date,
                'currency_id' => $request->currency_id,
                'exchange_rate' => 1.00, // Placeholder
                'subtotal' => 0, 
                'tax_total' => 0,
                'total' => 0,
                'status' => 'registered',
                'observations' => $request->observations,
            ]);

            $totalTaxable = 0;
            $totalTax = 0; 
            
            // Warehouse for stock
            $warehouse = null;
            if ($request->has('update_stock') && $request->warehouse_id) {
                $warehouse = Warehouse::find($request->warehouse_id);
            }

            // 2. Items & Stock Increment
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                $quantity = $itemData['quantity'];
                $cost = $itemData['unit_price'];

                $taxRate = 0.18; // IGV (Peru)
                $lineSubtotal = $quantity * $cost;
                $lineTax = $lineSubtotal * $taxRate; 
                $lineTotal = $lineSubtotal + $lineTax;

                PurchaseDocumentItem::create([
                    'purchase_document_id' => $purchase->id,
                    'product_id' => $product->id,
                    'description' => $product->name,
                    'quantity' => $quantity,
                    'unit_id' => $product->unit_id ?? 1, 
                    'unit_cost' => $cost,
                    'line_subtotal' => $lineSubtotal,
                    'line_tax_total' => $lineTax,
                    'line_total' => $lineTotal
                ]);

                $totalTaxable += $lineSubtotal;
                $totalTax += $lineTax;

                // Stock Increment
                // Only if "Afecta Inventario" is checked AND product is not a service
                if ($warehouse && !$product->is_service) {
                    // Update Cost Price
                    $product->cost_price = $cost;
                    $product->save();

                    // Movement
                    StockMovement::create([
                        'company_id' => $purchase->company_id,
                        'branch_id' => $purchase->branch_id,
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id,
                        'type' => 'in',
                        'quantity' => $quantity,
                        'date' => $purchase->issue_date,
                        'source_type' => 'Compra',
                        'source_id' => $purchase->id,
                        'observations' => 'Compra ' . $purchase->series . '-' . $purchase->number
                    ]);

                    // Stock
                    $stock = Stock::firstOrNew([
                        'company_id' => $purchase->company_id,
                        'warehouse_id' => $warehouse->id,
                        'product_id' => $product->id
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) + $quantity;
                    $stock->save();
                }
            }

            // 3. Update Totals
            $purchase->subtotal = $totalTaxable;
            $purchase->tax_total = $totalTax; 
            $purchase->total = $purchase->subtotal + $purchase->tax_total;
            $purchase->save();

            DB::commit();

            return redirect()->route('purchases.documents.index')->with('success', 'Compra registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar compra: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $document = PurchaseDocument::with(['items', 'supplier', 'payments.paymentMethod', 'currency', 'company', 'branch'])
            ->findOrFail($id);
        
        return view('purchases.documents.show', compact('document'));
    }
}
