<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('current_company_id');
        if (!$companyId) return redirect()->route('select.company');

        $query = StockMovement::where('company_id', $companyId);

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $movements = $query->with('product', 'warehouse')->latest('date')->paginate(15);
        $warehouses = Warehouse::where('company_id', $companyId)->get();
        $products = Product::where('company_id', $companyId)->get(); // Should be optimized for large lists

        return view('inventory.movements.index', compact('movements', 'warehouses', 'products'));
    }

    public function create()
    {
         $companyId = session('current_company_id');
         $warehouses = Warehouse::where('company_id', $companyId)->get();
         // Basic Product list, ideally Ajax search
         $products = Product::where('company_id', $companyId)->where('is_service', false)->where('active', true)->get();

         return view('inventory.movements.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $companyId = session('current_company_id');
        $branchId = session('current_branch_id'); // Assuming movements are tied to the current branch context or warehouse's branch

        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:in,out,adjustment', // simplified for now
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:0.01',
            'observations' => 'nullable|string'
        ]);
        
        // Verify warehouse belongs to company
        $warehouse = Warehouse::find($request->warehouse_id);
        if ($warehouse->company_id != $companyId) abort(403);
        
        $product = Product::find($request->product_id);
        if ($product->company_id != $companyId) abort(403);
        
        try {
            DB::beginTransaction();

            // 1. Create Stock Movement
            StockMovement::create([
                'company_id' => $companyId,
                'branch_id' => $warehouse->branch_id, 
                'warehouse_id' => $warehouse->id,
                'product_id' => $product->id,
                'date' => $request->date,
                'type' => $request->type,
                'quantity' => $request->quantity,
                'cost_unit' => $product->cost_price, // Snapshot cost
                'source_type' => 'manual', 
                'observations' => $request->observations
            ]);

            // 2. Update Stock
            $stock = Stock::firstOrNew([
                'product_id' => $product->id,
                'warehouse_id' => $warehouse->id
            ]);
            
            // If new, ensure quantity is initialized (it's non-nullable in DB but default might be missing in model)
            if (!$stock->exists) $stock->quantity = 0;

            if ($request->type == 'in') {
                $stock->quantity += $request->quantity;
            } elseif ($request->type == 'out') {
                 $stock->quantity -= $request->quantity;
            } elseif ($request->type == 'adjustment') {
                 // For now handling adjustment as addition (positive quantity adds to stock).
                 // Use negative quantity or 'out' for reduction if needed.
                 $stock->quantity += $request->quantity;
            }
            
            $stock->save();

            DB::commit();
            return redirect()->route('movements.index')->with('success', 'Movimiento registrado correctamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar movimiento: ' . $e->getMessage());
        }
    }
}
