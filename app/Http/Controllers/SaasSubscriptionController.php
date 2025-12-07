<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\CompanySubscription;
use App\Models\Plan;
use Illuminate\Http\Request;

class SaasSubscriptionController extends Controller
{
    public function update(Request $request, $companyId)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'required|string'
        ]);

        $company = Company::findOrFail($companyId);
        
        // Logic: Deactivate old active subscriptions?
        // Or just create a new one.
        // For SaaS Admin panel, we might validly want to edit the CURRENT one or Create New.
        // Let's assume creating a new overrides/ends previous? 
        // Or simpler: We update the "current" one if exists, or create.
        
        // Design choice: Create new one to keep history is cleaner, but for "Correction" we might want to edit.
        // Let's go with: Create New and mark others as inactive.
        
        CompanySubscription::where('company_id', $companyId)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        CompanySubscription::create([
            'company_id' => $company->id,
            'plan_id' => $request->plan_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'billing_period' => 'monthly', // Default
            'auto_renew' => true
        ]);

        return back()->with('success', 'Subscription updated successfully.');
    }
}
