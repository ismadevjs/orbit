<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    public function index()
    {
        return view('backend.currencies.currencies');
    }

    public function getCurrencies()
    {
        return datatables()->of(Currency::query())
            ->addIndexColumn()
            ->make(true);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:currencies,code',
            'exchange_rate' => 'required|numeric',
        ]);

        // Set default values for is_left and is_active
        $data = $request->all();
        $data['is_left'] = false;
        $data['is_active'] = true;

        Currency::create($data);

        return response()->json(['success' => 'Currency added successfully.']);
    }

    public function edit($id)
    {
        return response()->json(Currency::find($id));
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:currencies,id',
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:3|unique:currencies,code,' . $request->id,
            'exchange_rate' => 'required|numeric',
        ]);

        Currency::find($request->id)->update($request->all());

        return response()->json(['success' => 'Currency updated successfully.']);
    }

    public function destroy($id)
    {
        Currency::destroy($id);
        return response()->json(['success' => 'Currency deleted successfully.']);
    }

    public function updateSide(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:currencies,id',
            'is_left' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $currency = Currency::find($request->id);
        $currency->is_left = $request->is_left;
        $currency->save();

        return response()->json([
            'success' => true,
            'message' => 'Currency side updated successfully.'
        ]);
    }

    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:currencies,id',
            'is_active' => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $currency = Currency::find($request->id);
        $isActive = $request->is_active;

        // If the currency is being set to active, disable all other currencies
        if ($isActive) {
            // Disable all other currencies
            Currency::where('id', '!=', $currency->id)->update(['is_active' => false]);
        }

        // Update the current currency's active status
        $currency->is_active = $isActive;
        $currency->save();

        return response()->json([
            'success' => true,
            'message' => 'Currency status updated successfully.'
        ]);
    }

}
