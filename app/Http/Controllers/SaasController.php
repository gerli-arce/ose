<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaasController extends Controller
{
    // ====== TENANTS ======
    public function tenants()
    {
        // List all companies with their current subscription
        $tenants = Company::with(['subscriptions' => function($q) {
                $q->latest()->limit(1);
            }, 'subscriptions.plan']) // Add plan eager load
            ->withCount(['users', 'invoices']) // Show usage
            ->paginate(15);
            
        // Assuming we have relation users() and invoices() on Company model?
        // Invoice is actually 'sales_documents'?
        // Let's check Company model later, might need adjustment.
        
        return view('saas.tenants.index', compact('tenants'));
    }
    
    public function toggleTenantStatus($id)
    {
        $company = Company::findOrFail($id);
        $company->active = !$company->active;
        $company->save();
        
        return back()->with('success', 'Estado de empresa actualizado.');
    }

    // ====== PLANS ======
    public function plans()
    {
        $plans = Plan::all();
        return view('saas.plans.index', compact('plans'));
    }

    public function storePlan(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price_monthly' => 'required|numeric|min:0',
        ]);

        Plan::create($request->only('name', 'description', 'price_monthly', 'price_yearly'));
        return back()->with('success', 'Plan creado.');
    }

    // ====== SUBSCRIPTIONS ======
    // Assign Plan to Company
    public function assignPlan(Request $request)
    {
        $request->validate([
            'company_id' => 'required|exists:companies,id',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);
        
        // Deactivate old active subscriptions?
        CompanySubscription::where('company_id', $request->company_id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        CompanySubscription::create([
            'company_id' => $request->company_id,
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addMonth(), // Default 1 month
            'status' => 'active',
            'billing_period' => 'monthly',
            'auto_renew' => true
        ]);

        return back()->with('success', 'Plan asignado correctamente.');
    }
}
