<?php

namespace App\Http\Controllers;

use App\Models\ContractType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ContractTypeController extends Controller
{
    public function index()
    {
        return view('backend.contractTypes.contractTypes');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contractType = ContractType::create($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'ContractType created successfully!', 'contractType' => $contractType]);
    }

    public function update(Request $request, $id)
    {
        $contractType = ContractType::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $contractType->update($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'ContractType updated successfully!', 'contractType' => $contractType]);
    }

    public function destroy($id)
    {
        $contractType = ContractType::findOrFail($id);
        $contractType->delete();

        return response()->json(['success' => true, 'message' => 'ContractType deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(ContractType::query())
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $contractType = ContractType::findOrFail($id);
        return response()->json($contractType);
    }
}
