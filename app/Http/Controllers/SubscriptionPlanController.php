<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        return view('backend.subscriptions.subscriptions');
    }

    public function apiIndex()
    {
        return datatables()->of(SubscriptionPlan::query())->addIndexColumn()->toJson();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'minimum_deposit' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'bonus' => 'required|numeric|min:0',
            'bonus_duration' => 'required|in:hourly,daily,monthly,yearly',
            
        ]);

        SubscriptionPlan::create($request->all());

        return response()->json(['message' => 'Subscription Plan created successfully']);
    }

    public function show($id)
    {
        return response()->json(SubscriptionPlan::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $plan = SubscriptionPlan::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'minimum_deposit' => 'required|numeric|min:0',
            'percentage' => 'required|numeric|min:0|max:100',
            'bonus' => 'required|numeric|min:0',
            'bonus_duration' => 'required|in:hourly,daily,monthly,yearly',
         
        ]);

        $plan->update($request->all());

        return response()->json(['message' => 'Subscription Plan updated successfully']);
    }

    public function destroy($id)
    {
        SubscriptionPlan::findOrFail($id)->delete();

        return response()->json(['message' => 'Subscription Plan deleted successfully']);
    }
}
