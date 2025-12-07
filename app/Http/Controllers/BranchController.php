<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companyId = session('current_company_id');
        if (!$companyId) {
            return redirect()->route('select.company');
        }

        $branches = Branch::where('company_id', $companyId)->get();

        $branchesData = $branches->map(function($branch) {
            return [
                'id' => $branch->id,
                'name' => $branch->name,
                'code' => $branch->code,
                // 'address' => $branch->address ? $branch->address->full_address : '', 
                'active' => $branch->active,
                'status_label' => $branch->active ? 'Activa' : 'Inactiva',
                'edit_url' => route('branches.edit', $branch->id),
                'delete_url' => route('branches.destroy', $branch->id)
            ];
        });

        // Config Data for Selects
        $warehouses = []; 
        $series = []; 

        return view('branches.index', compact('branchesData', 'warehouses', 'series'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $companyId = session('current_company_id');

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50', 
            'address' => 'nullable|string',
            'active' => 'boolean'
        ]);

        $branch = new Branch();
        $branch->company_id = $companyId;
        $branch->name = $request->name;
        $branch->code = $request->code;
        $branch->active = $request->has('active') && $request->active == 'on'; 
        $branch->address = $request->address;
        
        $branch->save();

        return response()->json(['success' => true, 'message' => 'Sucursal creada exitosamente.']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Branch $branch)
    {
        $companyId = session('current_company_id');
        
        // Check access
        if ($branch->company_id != $companyId) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json(['branch' => $branch]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Branch $branch)
    {
        $companyId = session('current_company_id');
        
        if ($branch->company_id != $companyId) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            // 'active' => 'boolean' // Checkbox might rely on "presence means true" logic or explicit value
        ]);

        $branch->name = $request->name;
        $branch->code = $request->code;
        $branch->address = $request->address;
        $branch->active = $request->has('active') && $request->active == 'on' ? true : false;

        $branch->save();

        return response()->json(['success' => true, 'message' => 'Sucursal actualizada exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Branch $branch)
    {
        $companyId = session('current_company_id');
        
        if ($branch->company_id != $companyId) {
             return response()->json(['error' => 'Unauthorized'], 403);
        }

        $branch->delete();

        return response()->json(['success' => true, 'message' => 'Sucursal eliminada exitosamente.']);
    }
}
