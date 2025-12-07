<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class BranchSelectionController extends Controller
{
    public function show()
    {
        $companyId = session('current_company_id');
        
        if (!$companyId) {
            return redirect()->route('select.company');
        }

        $company = Company::find($companyId);
        $user = Auth::user();
        
        // TODO: In future, filter branches by user access (pivot table)
        // For now, assume user has access to all branches of the company OR check generic permission
        $branches = $company->branches;

        if ($branches->isEmpty()) {
             // Edge case: Company created but no branch. Create default?
             return redirect()->route('dashboard')->with('warning', 'Esta empresa no tiene sucursales activas.');
        }

        if ($branches->count() === 1) {
            return $this->selectBranchId($branches->first()->id);
        }

        return view('auth.select-branch', compact('branches', 'company'));
    }

    public function select(Request $request)
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id'
        ]);

        return $this->selectBranchId($request->branch_id);
    }

    private function selectBranchId($branchId)
    {
        $companyId = session('current_company_id');
        $company = Company::findOrFail($companyId);

        // Verify branch belongs to valid company
        $branch = $company->branches()->where('id', $branchId)->first();
        
        if (!$branch) {
            return redirect()->route('select.branch')->withErrors(['branch' => 'Sucursal inválida.']);
        }

        session(['current_branch_id' => $branchId]);

        return redirect()->route('dashboard');
    }
}
