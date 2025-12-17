<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $currentCompany = Auth::user()->companies->first();
        if (!$currentCompany) {
            return redirect()->route('dashboard')->with('error', 'No tienes una empresa asignada.');
        }

        // Obtener categorías para el listado (Grid)
        $categoriesData = ProductCategory::where('company_id', $currentCompany->id)
            ->with(['parent', 'products'])
            ->get()
            ->map(function ($cat) {
                return [
                    'id' => $cat->id,
                    'name' => $cat->name,
                    'code' => $cat->code,
                    'parent_name' => $cat->parent ? $cat->parent->name : '-',
                    'active' => $cat->active,
                    'products_count' => $cat->products->count(),
                    'created_at' => $cat->created_at->format('Y-m-d H:i')
                ];
            });

        // Obtener todas las categorías para el select de "Categoría Padre"
        // Excluimos las que podrían causar recursión infinita en el frontend se podría filtrar más si se necesita edit
        $allCategories = ProductCategory::where('company_id', $currentCompany->id)->get();

        return view('inventory.categories.index', compact('categoriesData', 'allCategories'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:product_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $currentCompany = Auth::user()->companies->first();

        ProductCategory::create([
            'company_id' => $currentCompany->id,
            'name' => $request->name,
            'code' => $request->code,
            'parent_id' => $request->parent_id,
            'active' => $request->has('active') ? true : false,
        ]);

        return response()->json(['success' => true, 'message' => 'Categoría creada exitosamente.']);
    }

    public function edit(ProductCategory $category)
    {
        // Verificar pertenencia a la empresa
        if ($category->company_id !== Auth::user()->companies->first()->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }
        
        return response()->json(['category' => $category]);
    }

    public function update(Request $request, ProductCategory $category)
    {
        if ($category->company_id !== Auth::user()->companies->first()->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'parent_id' => 'nullable|exists:product_categories,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Evitar que sea padre de sí mismo
        if ($request->parent_id == $category->id) {
             return response()->json(['errors' => ['parent_id' => ['Una categoría no puede ser padre de sí misma.']]], 422);
        }

        $category->update([
            'name' => $request->name,
            'code' => $request->code,
            'parent_id' => $request->parent_id,
            'active' => $request->has('active') ? true : false,
        ]);

        return response()->json(['success' => true, 'message' => 'Categoría actualizada exitosamente.']);
    }

    public function destroy(ProductCategory $category)
    {
        if ($category->company_id !== Auth::user()->companies->first()->id) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        // Verificar si tiene subcategorías o productos
        if ($category->children()->count() > 0 || $category->products()->count() > 0) {
            return response()->json(['error' => 'No se puede eliminar la categoría porque tiene subcategorías o productos asociados.'], 422);
        }

        $category->delete();

        return response()->json(['success' => true, 'message' => 'Categoría eliminada correctamente.']);
    }
}
