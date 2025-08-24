<?php

namespace App\Http\Controllers;

use App\Models\PricingPlan;
use App\Models\Tag;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PricingPlanController extends Controller
{
    // Display all pricing plans
    public function index()
    {
        return view('backend.plans.plans');
    }


    // Create a new pricing plan
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'min_amount' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'bonus' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'msg_investor' => 'nullable|string',
        ]);

        $plan = PricingPlan::create($data);

        return response()->json(['message' => 'Pricing plan created successfully', 'plan' => $plan], 201);
    }

    // Update a pricing plan
    public function update(Request $request, $id)
    {

    
        $plan = PricingPlan::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'min_amount' => 'sometimes|required|numeric|min:0',
            'percentage' => 'sometimes|required|numeric|min:0|max:100',
            'bonus' => 'nullable|numeric|min:0',
            'features' => 'nullable|array',
            'msg_investor' => 'nullable|string',
        ]);

        $plan->update($data);

        return response()->json(['message' => 'Pricing plan updated successfully', 'plan' => $plan]);
    }

    // Delete a pricing plan
    public function destroy($id)
    {
        $plan = PricingPlan::findOrFail($id);
        $plan->delete();

        return response()->json(['message' => 'Pricing plan deleted successfully']);
    }
    public function apiIndex()
    {
        return DataTables::of(PricingPlan::query())
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $tag = PricingPlan::findOrFail($id);
        return response()->json($tag);
    }

}
