<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ColorController extends Controller
{
    public function index()
    {
        return view('backend.colors.colors');
    }

    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'color_code' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:4096',
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $data = [
        'name' => $request->input('name'),
        'color_code' => $request->input('color_code'),
    ];

    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('colors', 'public');
    }

    Color::create($data);

    return response()->json(['success' => true, 'message' => 'Color created successfully!']);
}


public function update(Request $request, $id)
{
    $color = Color::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'color_code' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422);
    }

    try {
        $data = [
            'name' => $request->input('name'),
            'color_code' => $request->input('color_code'),
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($color->image) {
                \Storage::disk('public')->delete($color->image);
            }
            $data['image'] = $request->file('image')->store('colors', 'public');
        }

        $color->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Color updated successfully!'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to update color: ' . $e->getMessage()
        ], 500);
    }
}

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();

        return response()->json(['success' => true, 'message' => 'Color deleted successfully!']);
    }

    public function apiIndex(Request $request)
    {
        $colors = Color::query();
        return DataTables::of($colors)->addIndexColumn()->make(true);
    }

    public function show($id)
    {
        $color = Color::findOrFail($id);
        return response()->json($color);
    }
}
