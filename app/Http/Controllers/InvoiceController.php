<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\DocumentType;
use App\Models\Branch;
use App\Models\DocumentSeries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index()
    {
        $currentCompany = Auth::user()->companies->first();
        if (!$currentCompany) {
            return redirect()->route('dashboard')->with('error', 'No empresa asignada.');
        }

        // Fetch Invoices
        $invoices = Invoice::where('company_id', $currentCompany->id)
            ->with(['branch', 'documentType', 'series', 'customer'])
            ->latest('issue_date')
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('sales.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $currentCompany = Auth::user()->companies->first();
        
        // Data for form
        $documentTypes = DocumentType::where('affects_stock', '>=', 0)->get(); // All sales types
        $series = DocumentSeries::where('company_id', $currentCompany->id)
            ->with('documentType')
            ->get();
        
        $clients = \App\Models\Contact::where('company_id', $currentCompany->id)
            ->whereIn('type', ['customer', 'both'])
            ->get();

        $currencies = \App\Models\Currency::all();
        $paymentMethods = \App\Models\PaymentMethod::all();
        
        // Return view
        return view('sales.invoices.create', compact('documentTypes', 'series', 'clients', 'currencies', 'paymentMethods'));
    }

    public function searchProducts(Request $request)
    {
        $term = $request->term;
        $companyId = Auth::user()->companies->first()->id;

        $products = \App\Models\Product::where('company_id', $companyId)
            ->where('active', true)
            ->where(function($q) use ($term) {
                $q->where('name', 'LIKE', "%{$term}%")
                  ->orWhere('code', 'LIKE', "%{$term}%")
                  ->orWhere('barcode', 'LIKE', "%{$term}%");
            })
            ->take(20)
            ->get();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:contacts,id',
            'series_id' => 'required|exists:document_series,id',
            'document_type_id' => 'required|exists:document_types,id',
            'issue_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $currentCompany = Auth::user()->companies->first();
            $series = DocumentSeries::lockForUpdate()->find($request->series_id);
            $nextNumber = $series->current_number + 1;
            
            // 1. Create Invoice
            $invoice = Invoice::create([
                'company_id' => $currentCompany->id,
                'branch_id' => $series->branch_id,
                'document_type_id' => $request->document_type_id,
                'series_id' => $series->id,
                'number' => $nextNumber,
                'issue_date' => $request->issue_date,
                'due_date' => $request->due_date ?? $request->issue_date,
                'customer_id' => $request->customer_id,
                'currency_id' => $request->currency_id,
                'exchange_rate' => 1.00, // TODO: Fetch real rate
                'subtotal' => 0, // Calculated below
                'tax_total' => 0,
                'total' => 0,
                'status' => 'issued',
                'payment_status' => 'unpaid',
                'observations' => $request->observations,
            ]);

            $totalTaxable = 0;
            $totalTax = 0;

            // 2. Create Items & Deduct Stock
            foreach ($request->items as $itemData) {
                // Fetch product for reliable price/cost? Or trust input? 
                // Trusting input for price (users might modify it), but verify product exists.
                $product = \App\Models\Product::find($itemData['product_id']);
                
                $quantity = $itemData['quantity'];
                $price = $itemData['unit_price'];
                $lineTotal = $quantity * $price;

                $invoiceItem = InvoiceItem::create([
                    'sales_document_id' => $invoice->id,
                    'product_id' => $product->id,
                    'description' => $product->name, // Or input description
                    'quantity' => $quantity,
                    'unit_id' => $product->unit_id ?? 1, // Fallback
                    'unit_price' => $price,
                    'line_subtotal' => $lineTotal,
                    'line_tax_total' => $lineTotal * 0.18, // IGV 18% assumption
                    'line_total' => $lineTotal * 1.18,
                ]);

                $totalTaxable += $lineTotal;
                
                // Stock Deduction
                if ($invoice->documentType->affects_stock == 1 && !$product->is_service) {
                    // Create Stock Movement (Out)
                    \App\Models\StockMovement::create([
                        'company_id' => $invoice->company_id,
                        'branch_id' => $invoice->branch_id,
                        'warehouse_id' => $series->warehouse_id ?? \App\Models\Warehouse::where('branch_id', $invoice->branch_id)->first()->id, // Fallback to first warehouse of branch
                        'product_id' => $product->id,
                        'type' => 'out',
                        'quantity' => $quantity,
                        'date' => $invoice->issue_date,
                        'source_type' => 'Venta',
                        'source_id' => $invoice->id,
                        'observations' => 'Venta ' . $invoice->series_number
                    ]);

                    // Update Stock Record
                    $warehouseId = $series->warehouse_id ?? \App\Models\Warehouse::where('branch_id', $invoice->branch_id)->first()->id;
                    $stock = \App\Models\Stock::firstOrNew([
                        'company_id' => $invoice->company_id,
                        'warehouse_id' => $warehouseId,
                        'product_id' => $product->id
                    ]);
                    $stock->quantity = ($stock->quantity ?? 0) - $quantity;
                    $stock->save();
                }
            }

            // 3. Update Totals
            $invoice->subtotal = $totalTaxable;
            $invoice->tax_total = $totalTaxable * 0.18;
            $invoice->total = $invoice->subtotal + $invoice->tax_total;
            $invoice->save();

            // 4. Update Series Number
            $series->current_number = $nextNumber;
            $series->save();

            DB::commit();

            return redirect()->route('invoices.index')->with('success', 'Venta registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar venta: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $invoice = Invoice::with(['items.product', 'customer', 'branch', 'company'])->findOrFail($id);
        return view('sales.invoices.show', compact('invoice'));
    }
}
