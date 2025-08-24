<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;
use Yajra\DataTables\DataTables;

// Ensure this model exists

class FeatureController extends Controller
{
    public function index()
    {
        return view('backend.features.features'); // Adjust the view name accordingly
    }

    public function store(Request $request)
    {

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $path = $request->file('image')->store('images/sections', 'public');

        Feature::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'image' => $path
        ]);

        return back()->withSuccess('Feature created successfully!');
    }

    public function update(Request $request, $id)
    {
        \Log::info($request->file('image'));
        $feature = Feature::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:8192'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

       
        $data['name'] = $request->input('name');
        $data['description'] = $request->input('description');
        if ($request->hasFile('image')) {
            // Delete the old image if needed
            if ($feature->image) {
                Storage::delete($feature->image); // Assumes the image path is stored in the 'image' column
            }
            $path = $request->file('image')->store('images/features', 'public'); // Save to storage/app/public/images/features
            $feature->image = $path; // Store the path in the database
        }
        $feature->update($data);

        return back()->withSuccess('Feature updated successfully!');
    }

    public function destroy($id)
    {
        $feature = Feature::findOrFail($id);
        $feature->delete();

        return response()->json(['success' => true, 'message' => 'Feature deleted successfully!']);
    }

    // API section for DataTables
    public function apiIndex(Request $request)
    {
        $features = Feature::query();

        return DataTables::of($features)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $feature = Feature::findOrFail($id);
        return response()->json($feature);
    }
}
