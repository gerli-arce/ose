<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function index()
    {
        $companyId = session('current_company_id');
        if (!$companyId) return redirect()->route('select.company');

        $warehouses = Warehouse::where('company_id', $companyId)->with('branch')->get();
        return view('inventory.warehouses.index', compact('warehouses'));
    }

    public function create()
    {
         // Usually modal, but if page needed:
         return view('inventory.warehouses.create');
    }

    public function store(Request $request)
    {
        $companyId = session('current_company_id');
        
        $request->validate([
            'name' => 'required|string|max:255',
            'branch_id' => 'required|exists:branches,id',
        ]);
        
        // Verify branch belongs to company
        $branch = \App\Models\Branch::find($request->branch_id);
        if ($branch->company_id != $companyId) abort(403, 'Invalid Branch');

        Warehouse::create([
            'company_id' => $companyId,
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'code' => $request->code,
            'address_id' => null, // Placeholder if address logic needed
            'active' => $request->has('active')
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Almacén creado.');
    }

    public function edit(Warehouse $warehouse)
    {
        $companyId = session('current_company_id');
        if ($warehouse->company_id != $companyId) abort(403);
        
        return response()->json($warehouse); // If using modal, JSON is useful
    }

    public function update(Request $request, Warehouse $warehouse)
    {
        $companyId = session('current_company_id');
        if ($warehouse->company_id != $companyId) abort(403);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $warehouse->update([
            'name' => $request->name,
            'code' => $request->code,
            'active' => $request->has('active')
        ]);

        return redirect()->route('warehouses.index')->with('success', 'Almacén actualizado.');
    }
    
    public function destroy(Warehouse $warehouse)
    {
         $companyId = session('current_company_id');
        if ($warehouse->company_id != $companyId) abort(403);
        
        $warehouse->delete();
        return redirect()->route('warehouses.index')->with('success', 'Almacén eliminado.');
    }
}
