<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;

class CompanySelectionController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $companies = $user->companies;

        // If user has no companies, show error or create company page (not implemented yet)
        if ($companies->isEmpty()) {
            Auth::logout();
            return redirect()->route('login')->withErrors(['email' => 'Usuario no asignado a ninguna empresa.']);
        }

        // If user has only 1 company, auto-select it and redirect
        if ($companies->count() === 1) {
            return $this->selectCompanyId($companies->first()->id);
        }

        return view('auth.select-company', compact('companies'));
    }

    public function select(Request $request) 
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id'
        ]);
        
        return $this->selectCompanyId($request->company_id);
    }

    private function selectCompanyId($companyId)
    {
        $user = Auth::user();

        // Verify user belongs to this company
        if (!$user->companies->contains($companyId)) {
            return redirect()->route('select.company')->withErrors(['company' => 'Acceso no autorizado a esta empresa.']);
        }

        // Store selection in session
        session(['current_company_id' => $companyId]);
        
        // Clear branch selection just in case
        session()->forget('current_branch_id');

        return redirect()->route('select.branch');
    }
}
