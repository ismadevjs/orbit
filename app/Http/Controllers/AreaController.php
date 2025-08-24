<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Storage;

class AreaController extends Controller
{

    public function index()
    {
        return view('backend.areas.areas');
    }

    public function store(Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192', // Validate image
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('areas', 'public');
        }



        Area::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'latitude' => $request->input('latitude'),
            'longitude' => $request->input('longitude'),
            'image' => $imagePath, // Store image path
        ]);

        return back()->withSuccess('Area created successfully!');
    }

    public function update(Request $request, $id)
{
    $area = Area::findOrFail($id);

    // Validation
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'latitude' => 'nullable|numeric',
        'longitude' => 'nullable|numeric',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192', // Validate image
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator->errors());
    }

    // Handle image upload
    $imagePath = $area->image; // Keep existing image
    if ($request->hasFile('image')) {
        // Optionally delete the old image if needed
        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }
        $imagePath = $request->file('image')->store('areas', 'public');
    }

    $area->update([
        'name' => $request->input('name'),
        'description' => $request->input('description'),
        'latitude' => $request->input('latitude'),
        'longitude' => $request->input('longitude'),
        'image' => $imagePath, // Update image path
    ]);

    return back()->withSuccess('Area updated successfully!');
}
    public function destroy($id)
    {
        $area = Area::findOrFail($id);
        $area->delete();

        return response()->json(['success' => true, 'message' => 'Area deleted successfully!']);
    }

    // API section for DataTables
    public function apiIndex(Request $request)
    {
        $areas = Area::query();

        return DataTables::of($areas)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $area = Area::findOrFail($id);
        return response()->json($area);
    }
}
