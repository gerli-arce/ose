<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanFeature;
use Illuminate\Http\Request;

class SaasPlanController extends Controller
{
    public function index()
    {
        $plans = Plan::with('features')->get();
        return view('saas.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('saas.plans.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
            'price_yearly' => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $plan = Plan::create($request->only('name', 'price_monthly', 'price_yearly', 'description'));

        if ($request->has('features')) {
            foreach ($request->features as $key => $value) {
                if ($value) {
                    PlanFeature::create([
                        'plan_id' => $plan->id,
                        'key' => $key,
                        'value' => $value
                    ]);
                }
            }
        }

        return redirect()->route('saas.plans.index')->with('success', 'Plan created successfully.');
    }

    public function edit(Plan $plan)
    {
        return view('saas.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price_monthly' => 'required|numeric|min:0',
        ]);

        $plan->update($request->only('name', 'price_monthly', 'price_yearly', 'description'));

        // Handle Features (Naive implementation: delete all and recreate, or update)
        // For simplicity in this iteration:
        if ($request->has('features')) {
            $plan->features()->delete();
            foreach ($request->features as $key => $value) {
                 if ($value) {
                    PlanFeature::create([
                        'plan_id' => $plan->id,
                        'key' => $key,
                        'value' => $value
                    ]);
                }
            }
        }

        return redirect()->route('saas.plans.index')->with('success', 'Plan updated successfully.');
    }
}
