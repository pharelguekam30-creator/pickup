<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectionPlan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = CollectionPlan::orderBy('type')->orderBy('price_per_month')->get();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.form');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:familial,entreprise',
            'collections_per_week' => 'required|integer|min:1|max:7',
            'collection_days' => 'required|array',
            'collection_days.*' => 'string',
            'price_per_month' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        CollectionPlan::create($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan créé avec succès.');
    }

    public function edit(CollectionPlan $plan)
    {
        return view('admin.plans.form', compact('plan'));
    }

    public function update(Request $request, CollectionPlan $plan)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:familial,entreprise',
            'collections_per_week' => 'required|integer|min:1|max:7',
            'collection_days' => 'required|array',
            'collection_days.*' => 'string',
            'price_per_month' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        $plan->update($data);

        return redirect()->route('admin.plans.index')->with('success', 'Plan mis à jour.');
    }

    public function destroy(CollectionPlan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan supprimé.');
    }
}
