<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\UnitOfMeasure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $companyId = session('current_company_id');
        if (!$companyId) return redirect()->route('select.company');

        $query = Product::where('company_id', $companyId);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('product_category_id', $request->category_id);
        }
        
        if ($request->filled('type')) {
             if ($request->type == 'service') $query->where('is_service', true);
             if ($request->type == 'product') $query->where('is_service', false);
        }

        $products = $query->with('category', 'unit')->orderBy('name')->paginate(15);
        $categories = ProductCategory::where('company_id', $companyId)->get();

        return view('inventory.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $companyId = session('current_company_id');
        $categories = ProductCategory::where('company_id', $companyId)->get();
        $units = UnitOfMeasure::all(); // Assuming these are global or seeded
        
        return view('inventory.products.create', compact('categories', 'units'));
    }

    public function store(Request $request)
    {
        $companyId = session('current_company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50', // Add unique check manually
            'product_category_id' => 'nullable|exists:product_categories,id',
            'unit_id' => 'required|exists:unit_of_measures,id',
            'sale_price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048'
        ]);
        
        // Manual unique check for code within company
        if (Product::where('company_id', $companyId)->where('code', $request->code)->exists()) {
             return back()->withErrors(['code' => 'El código ya existe en esta empresa.'])->withInput();
        }

        $data = $request->except('image');
        $data['company_id'] = $companyId;
        $data['is_service'] = $request->has('is_service');
        $data['active'] = $request->has('active');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show(Product $product)
    {
        $companyId = session('current_company_id');
        if ($product->company_id != $companyId) abort(403);
        
        $stockByWarehouse = $product->stocks()->with('warehouse')->get();
        $totalStock = $stockByWarehouse->sum('quantity');
        
        return view('inventory.products.show', compact('product', 'stockByWarehouse', 'totalStock'));
    }

    public function edit(Product $product)
    {
        $companyId = session('current_company_id');
        if ($product->company_id != $companyId) abort(403);

        $categories = ProductCategory::where('company_id', $companyId)->get();
        $units = UnitOfMeasure::all();

        return view('inventory.products.edit', compact('product', 'categories', 'units'));
    }

    public function update(Request $request, Product $product)
    {
        $companyId = session('current_company_id');
        if ($product->company_id != $companyId) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'unit_id' => 'required|exists:unit_of_measures,id',
            'sale_price' => 'nullable|numeric|min:0',
            'image' => 'nullable|image|max:2048'
        ]);

        // Unique check ignoring self
        if (Product::where('company_id', $companyId)->where('code', $request->code)->where('id', '!=', $product->id)->exists()) {
             return back()->withErrors(['code' => 'El código ya existe en esta empresa.'])->withInput();
        }

        $data = $request->except('image');
        $data['is_service'] = $request->has('is_service');
        $data['active'] = $request->has('active');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image_path) {
                $oldPath = str_replace('storage/', '', $product->image_path);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('products', 'public');
            $data['image_path'] = 'storage/' . $path;
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
         $companyId = session('current_company_id');
        if ($product->company_id != $companyId) abort(403);
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado.');
    }
}
