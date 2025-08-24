<?php

namespace App\Http\Controllers;

use App\Models\VatAndTax;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class VatAndTaxController extends Controller
{
    public function index()
    {
        $taxs = VatAndTax::paginate(10);
        return view('backend.vat_and_tax.vat_and_tax', compact('taxs'));
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        VatAndTax::create([
            'name' => $request->input('name'),
            'rate' => $request->input('rate'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
            'is_active' => true,
        ]);
        return back()->withSuccess('VAT & TAX created successfully!');
    }

    public function update(Request $request, $id)
    {
        $vatAndTax = VatAndTax::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'type' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $vatAndTax->update([
            'name' => $request->input('name'),
            'rate' => $request->input('rate'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
        ]);

        return back()->withSuccess('VAT & TAX updated successfully!');
    }

    public function destroy($id)
    {
        $vatAndTax = VatAndTax::findOrFail($id);
        $vatAndTax->delete();

        return response()->json(['success' => true, 'message' => 'VAT & TAX deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(VatAndTax::query())
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $vatAndTax = VatAndTax::findOrFail($id);
        return response()->json($vatAndTax);
    }
}
