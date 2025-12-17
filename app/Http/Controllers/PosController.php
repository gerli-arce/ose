<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use App\Models\Contact;

use App\Models\Currency;
use App\Models\DocumentSeries;
use App\Models\DocumentType;
use App\Models\IdentityDocumentType;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\SalesDocument;
use App\Models\SalesDocumentItem;
use App\Models\SalesPayment;
use App\Models\SalesPaymentAllocation;
use App\Models\Stock;
use App\Jobs\SendDocumentToSunatJob;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PosController extends Controller
{
    /**
     * Vista principal del POS
     */
    public function index()
    {
        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        // Productos activos con stock
        $products = Product::where('company_id', $companyId)
            ->where('active', true)
            ->with(['category', 'stocks'])
            ->orderBy('name')
            ->get();

        // Categorías para filtro
        $categories = ProductCategory::where('company_id', $companyId)
            ->where('active', true)
            ->orderBy('name')
            ->get();

        // Series disponibles para Factura (01) y Boleta (03)
        $series = DocumentSeries::where('company_id', $companyId)
            ->whereIn('document_type_id', function($query) {
                $query->select('id')
                    ->from('document_types')
                    ->whereIn('code', ['01', '03']);
            })
            ->with('documentType')
            ->get();

        // Métodos de pago
        $paymentMethods = PaymentMethod::where('active', true)->get();

        // Monedas
        $currencies = Currency::where('active', true)->get();

        // Cliente genérico
        $genericCustomer = Contact::where('company_id', $companyId)
            ->where('tax_id', '00000000')
            ->first();

        // Configuración POS del usuario/sucursal
        $posConfig = $this->getPosConfig();

        return view('pos.index', compact(
            'products', 
            'categories', 
            'series', 
            'paymentMethods',
            'currencies',
            'genericCustomer',
            'posConfig'
        ));
    }

    /**
     * Búsqueda de productos
     */
    public function searchProducts(Request $request)
    {
        $companyId = session('current_company_id');
        $search = $request->input('q');
        $categoryId = $request->input('category_id');

        $query = Product::where('company_id', $companyId)
            ->where('active', true);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', $search);
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->with('category')
            ->limit(50)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'barcode' => $product->barcode,
                    'price' => $product->sale_price ?? $product->price,
                    'category' => $product->category?->name,
                    'image' => $product->image_url ?? null,
                    'stock' => $product->current_stock ?? 0,
                    'unit_code' => $product->unit_code ?? 'NIU',
                ];
            });

        return response()->json($products);
    }

    /**
     * Búsqueda de clientes
     */
    public function searchCustomers(Request $request)
    {
        $companyId = session('current_company_id');
        $search = $request->input('q');

        $customers = Contact::where('company_id', $companyId)
            ->whereIn('type', ['customer', 'both'])
            ->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('tax_id', 'like', "%{$search}%");
            })
            ->limit(20)
            ->get()
            ->map(function($customer) {
                return [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'tax_id' => $customer->tax_id,
                    'identity_type' => $customer->sunat_doc_type_code,
                    'address' => $customer->address,
                    'email' => $customer->email,
                ];
            });

        return response()->json($customers);
    }

    /**
     * Procesar venta
     */
    public function processSale(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:contacts,id',
            'series_id' => 'required|exists:document_series,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'discount_total' => 'nullable|numeric|min:0',
            'amount_received' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
            'send_to_sunat' => 'boolean',
        ]);

        $companyId = session('current_company_id');
        $branchId = session('current_branch_id');

        DB::beginTransaction();

        try {
            // Obtener serie y siguiente número
            $series = DocumentSeries::findOrFail($validated['series_id']);
            $documentType = $series->documentType;
            $nextNumber = $series->current_number + 1;

            // Calcular totales
            $subtotal = 0;
            $taxTotal = 0;
            $discountTotal = floatval($validated['discount_total'] ?? 0);
            
            foreach ($validated['items'] as $item) {
                $qty = floatval($item['quantity']);
                $price = floatval($item['unit_price']);
                $discount = floatval($item['discount'] ?? 0);
                
                $lineSubtotal = ($qty * $price) - $discount;
                $subtotal += $lineSubtotal;
            }

            $subtotal -= $discountTotal;
            $taxTotal = $subtotal * 0.18;
            $total = $subtotal + $taxTotal;

            // Crear documento de venta
            $salesDocument = SalesDocument::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'document_type_id' => $documentType->id,
                'series_id' => $series->id,
                'number' => $nextNumber,
                'customer_id' => $validated['customer_id'],
                'currency_id' => 1, // PEN por defecto
                'issue_date' => Carbon::today(),
                'due_date' => Carbon::today(),
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'tax_total' => $taxTotal,
                'total' => $total,
                'status' => 'emitted',
                'payment_status' => 'paid',
                'notes' => $validated['notes'] ?? null,
                'pos_sale' => true,
            ]);

            // Crear items
            foreach ($validated['items'] as $itemData) {
                $product = Product::find($itemData['product_id']);
                $qty = floatval($itemData['quantity']);
                $price = floatval($itemData['unit_price']);
                $discount = floatval($itemData['discount'] ?? 0);
                
                $lineSubtotal = ($qty * $price) - $discount;
                $lineTax = $lineSubtotal * 0.18;
                $lineTotal = $lineSubtotal + $lineTax;

                SalesDocumentItem::create([
                    'sales_document_id' => $salesDocument->id,
                    'product_id' => $product->id,
                    'description' => $product->name,
                    'unit_code' => $product->unit_code ?? 'NIU',
                    'quantity' => $qty,
                    'unit_price' => $price,
                    'discount' => $discount,
                    'subtotal' => $lineSubtotal,
                    'tax_amount' => $lineTax,
                    'line_total' => $lineTotal,
                ]);

                // Descontar stock si aplica
                if ($product->affects_inventory) {
                    // Registrar movimiento de stock
                }
            }

            // Actualizar número de serie
            $series->update(['current_number' => $nextNumber]);

            // Registrar pago
            $payment = SalesPayment::create([
                'company_id' => $companyId,
                'branch_id' => $branchId,
                'customer_id' => $validated['customer_id'],
                'payment_method_id' => $validated['payment_method_id'],
                'payment_date' => Carbon::today(),
                'currency_id' => 1,
                'amount' => $total,
                'reference' => 'POS-' . $salesDocument->id,
            ]);

            SalesPaymentAllocation::create([
                'sales_payment_id' => $payment->id,
                'sales_document_id' => $salesDocument->id,
                'allocated_amount' => $total,
            ]);

            // Enviar a SUNAT si se requiere
            if ($request->boolean('send_to_sunat', true)) {
                SendDocumentToSunatJob::dispatch($salesDocument->id);
            }

            DB::commit();

            // Calcular vuelto
            $amountReceived = floatval($validated['amount_received'] ?? $total);
            $change = max(0, $amountReceived - $total);

            return response()->json([
                'success' => true,
                'message' => 'Venta procesada exitosamente',
                'document' => [
                    'id' => $salesDocument->id,
                    'full_number' => $series->prefix . '-' . str_pad($nextNumber, 8, '0', STR_PAD_LEFT),
                    'total' => $total,
                    'change' => $change,
                ],
                'print_url' => route('pdf.document.ticket', $salesDocument->id),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en venta POS: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la venta: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Crear cliente rápido
     */
    public function quickCustomer(Request $request)
    {
        $validated = $request->validate([
            'tax_id' => 'required|string|max:15',
            'name' => 'required|string|max:255',
            'identity_type' => 'required|in:1,6',
            'address' => 'nullable|string|max:500',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $companyId = session('current_company_id');

        try {
            $docType = IdentityDocumentType::where('code', $validated['identity_type'])->first();

            $customer = Contact::create([
                'company_id' => $companyId,
                'tax_id' => $validated['tax_id'],
                'name' => $validated['name'],
                'identity_document_type_id' => $docType ? $docType->id : null,
                'type' => 'customer',
                'address' => $validated['address'] ?? null,
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'tax_id' => $customer->tax_id,
                    'identity_type' => $validated['identity_type'],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear cliente: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Obtener configuración POS
     */
    private function getPosConfig(): array
    {
        $companyId = session('current_company_id');
        
        // Buscar serie por defecto para Boleta
        $defaultSeries = DocumentSeries::where('company_id', $companyId)
            ->whereHas('documentType', fn($q) => $q->where('code', '03'))
            ->first();

        return [
            'default_series_id' => $defaultSeries?->id,
            'default_print_format' => 'ticket',
            'auto_print' => true,
            'send_to_sunat' => true,
        ];
    }
}
