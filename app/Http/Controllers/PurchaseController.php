<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchaseItem;
use App\Models\DocumentType;
use App\Models\Branch;
use App\Models\StockMovement;
use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function index()
    {
        $currentCompany = Auth::user()->companies->first();
        if (!$currentCompany) {
            return redirect()->route('dashboard')->with('error', 'No empresa asignada.');
        }

        $purchases = Purchase::where('company_id', $currentCompany->id)
            ->with(['supplier', 'documentType'])
            ->latest('issue_date')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $currentCompany = Auth::user()->companies->first();
        
        $documentTypes = DocumentType::where('affects_stock', '>=', 0)->get(); // Adjust filter if needed for purchase docs
        // Actually, purchase docs usually affect stock positively (+1) or neutral.
        // Let's assume all doc types for now or filter specifically for purchases if we had a flag.
        // For now get all.
        $documentTypes = DocumentType::all();
        
        $suppliers = \App\Models\Contact::where('company_id', $currentCompany->id)
            ->whereIn('type', ['supplier', 'both'])
            ->get();

        $currencies = \App\Models\Currency::all();
        $branches = Branch::where('company_id', $currentCompany->id)->get();
        // Warehouses? We need to know where to put stock.
        // Fetch warehouses for the current branch (or all)
        $warehouses = \App\Models\Warehouse::where('company_id', $currentCompany->id)->get();

        return view('purchases.create', compact('documentTypes', 'suppliers', 'currencies', 'branches', 'warehouses'));
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
            'warehouse_id' => 'required|exists:warehouses,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $currentCompany = Auth::user()->companies->first();
            $warehouse = \App\Models\Warehouse::find($request->warehouse_id);

            // 1. Create Purchase
            $purchase = Purchase::create([
                'company_id' => $currentCompany->id,
                'branch_id' => $warehouse->branch_id, // Link to branch of the chosen warehouse
                'supplier_id' => $request->supplier_id,
                'document_type_id' => $request->document_type_id,
                'series' => strtoupper($request->series),
                'number' => $request->number,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date,
                'currency_id' => $request->currency_id,
                'exchange_rate' => 1.00, 
                'subtotal' => 0, 
                'tax_total' => 0,
                'total' => 0,
                'status' => 'registered',
                'observations' => $request->observations,
            ]);

            $totalTaxable = 0;
            // $totalTax = 0;

            // 2. Items & Stock Increment
            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['product_id']);
                
                $quantity = $itemData['quantity'];
                $cost = $itemData['unit_price'];
                $lineTotal = $quantity * $cost;

                $purchaseItem = PurchaseItem::create([
                    'purchase_document_id' => $purchase->id,
                    'product_id' => $product->id,
                    'description' => $product->name,
                    'quantity' => $quantity,
                    'unit_price' => $cost,
                    'line_total' => $lineTotal,
                ]);

                $totalTaxable += $lineTotal;

                // Stock Increment
                if (!$product->is_service) {
                    // Update Cost Price? Maybe Weighted Average?
                    // For now simplicity: Update last cost price
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

            // 3. Update Totals (Assuming input prices are excl Tax or incl? Let's assume Excl for B2B)
            // But usually validation requires total match. 
            // Calculated simple logic:
            $purchase->subtotal = $totalTaxable;
            $purchase->tax_total = $totalTaxable * 0.18; 
            $purchase->total = $purchase->subtotal + $purchase->tax_total;
            $purchase->save();

            DB::commit();

            return redirect()->route('purchases.index')->with('success', 'Compra registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar compra: ' . $e->getMessage())->withInput();
        }
    }
    
    // public function show($id)...
}
