<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Plan;
use Illuminate\Http\Request;

class SaasCompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with(['subscriptions.plan']); // Assuming hasMany subscriptions

        if ($request->has('status')) {
            if ($request->status == 'active') $query->where('active', 1);
            if ($request->status == 'suspended') $query->where('active', 0);
        }

        $companies = $query->paginate(20);

        return view('saas.companies.index', compact('companies'));
    }

    public function show($id)
    {
        $company = Company::with(['subscriptions' => function($q) {
            $q->latest()->limit(1);
        }, 'subscriptions.plan', 'users'])->findOrFail($id);
        
        $currentSubscription = $company->subscriptions->first();
        $plans = Plan::all();

        // Usage Stats
        $userCount = $company->users()->count();
        // Assuming SalesDocument logic
        $invoiceCount = \App\Models\SalesDocument::where('company_id', $company->id)
            ->whereMonth('issue_date', now()->month)
            ->whereYear('issue_date', now()->year)
            ->count();

        return view('saas.companies.show', compact('company', 'currentSubscription', 'plans', 'userCount', 'invoiceCount'));
    }

    public function updateStatus(Request $request, $id)
    {
        $company = Company::findOrFail($id);
        $company->active = !$company->active;
        $company->save();

        return back()->with('success', 'Company status updated.');
    }
}
