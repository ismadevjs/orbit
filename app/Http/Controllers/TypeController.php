<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TypeController extends Controller
{
    public function index()
    {
        return view('backend.types.types');
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

        $type = Type::create($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'Type created successfully!', 'type' => $type]);
    }

    public function update(Request $request, $id)
    {
        $type = Type::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type->update($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'Type updated successfully!', 'type' => $type]);
    }

    public function destroy($id)
    {
        $type = Type::findOrFail($id);
        $type->delete();

        return response()->json(['success' => true, 'message' => 'Type deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(Type::query())
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $type = Type::findOrFail($id);
        return response()->json($type);
    }
}
