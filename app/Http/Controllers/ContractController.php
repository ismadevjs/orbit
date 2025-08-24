<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ContractController extends Controller
{
    public function index()
    {
        return view('backend.contracts.contracts');
    }

    public function apiIndex()
    {
        return DataTables::of(Contract::query()->orderBy('status', 'asc'))
            ->addIndexColumn()
            ->addColumn('type_id', function ($row) {
                return $row->type ? $row->type->name : 'N/A';
            })
            ->make(true);
    }

    public function show($id)
    {
        return response()->json(Contract::findOrFail($id));
    }

    public function update(Request $request, $contractId)
    {
        $request->validate([
            'type_id' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        // Find the contract by ID
        $contract = Contract::find($contractId);

        if (!$contract) {
            return response()->json(['message' => 'Contract not found'], 404);
        }

        // Update the contract's properties
        $contract->name = $request->title;
        $contract->type_id = $request->type_id;
        $contract->content = $request->content;

        // Save the updated contract
        $contract->save();

        return response()->json($contract, 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'type_id' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);


        $old_contract = Contract::query()
            ->where('type_id', '=', $request->type_id)
            ->where('status', '=', 'ACTIVE')
            ->first();

        if ($old_contract) {
            $old_contract->status = 'INACTIVE';
            $old_contract->save();
        }

        $contract = new Contract();
        $contract->name = $request->title;
        $contract->status = 'ACTIVE';
        $contract->type_id = $request->type_id;
        $contract->content = $request->content;
        $contract->save();

        return response()->json($contract, 201);
    }

    public function destroy($id)
    {
        Contract::destroy($id);
        return response()->json(null, 204);
    }
}
