<?php

namespace App\Http\Controllers;

use App\Models\ContractTheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ContractThemeController extends Controller
{
    public function index()
    {
        return view('backend.contracts.contractThemes');
    }

    public function show($id)
    {
        $contractTheme = ContractTheme::findOrFail($id);
        return response()->json($contractTheme);
    }

    public function update(Request $request, $id)
    {
        $contractTheme = ContractTheme::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->input('name'),
        ];

        if ($request->hasFile('file')) {
            // Delete the old file
            if ($contractTheme->file) {
                Storage::disk('public')->delete($contractTheme->file);
            }

            // Store the new file
            $data['file'] = $request->file('file')->store('contractThemes', 'public');
        }

        $contractTheme->update($data);

        return response()->json([
            'success' => true,
            'message' => 'ContractTheme updated successfully!',
            'data' => $contractTheme
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'file' => 'nullable|file|mimes:pdf|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->input('name'),
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('contractThemes', 'public');
        }

        $contractTheme = ContractTheme::create($data);

        return response()->json([
            'success' => true,
            'message' => 'ContractTheme created successfully!',
            'data' => $contractTheme
        ]);
    }

    public function destroy($id)
    {
        $contractTheme = ContractTheme::findOrFail($id);

        if ($contractTheme->file) {
            Storage::disk('public')->delete($contractTheme->file);
        }

        $contractTheme->delete();

        return response()->json(['success' => true, 'message' => 'ContractTheme deleted successfully!']);
    }

    public function apiIndex()
    {
        $contractThemes = ContractTheme::query();

        return DataTables::of($contractThemes)
            ->addIndexColumn()
            ->addColumn('file', function ($row) {
                return $row->file ? $row->file : null;
            })
            ->addColumn('is_active', function ($row) {
                return $row->is_active ? 1 : 0;
            })
            ->make(true);
    }

    public function toggleActive(Request $request, $id)
    {
        $contractTheme = ContractTheme::findOrFail($id);

        // If trying to activate and it's already active, do nothing
        if ($request->input('is_active') && $contractTheme->is_active) {
            return response()->json(['success' => true, 'message' => 'No changes needed']);
        }

        // Deactivate all other themes if this one is being activated
        if ($request->input('is_active')) {
            ContractTheme::query()->update(['is_active' => false]);
        }

        // Update the current theme's active status
        $contractTheme->update(['is_active' => $request->input('is_active')]);

        return response()->json([
            'success' => true,
            'message' => 'Theme status updated successfully',
            'data' => $contractTheme
        ]);
    }
}
