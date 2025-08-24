<?php

namespace App\Http\Controllers;

use App\Models\LandingPageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class LandingPageSectionController extends Controller
{
    public function index()
    {
        $sections = LandingPageSection::all();
        return view('backend.sections.sections', compact('sections'));
    }

    public function apiIndex(Request $request)
    {
        $sections = LandingPageSection::query();

        return DataTables::of($sections)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $section = LandingPageSection::find($id);

        if (!$section) {
            return response()->json(['error' => 'Section not found'], 404);
        }

        return response()->json($section);
    }

    public function update(Request $request, $id)
{
    \Log::info($request->all());

    $validator = Validator::make($request->all(), [
        'title' => 'required|string|max:255',
        'content' => 'required|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        'name' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 422);
    }

    $section = LandingPageSection::find($id);

    if (!$section) {
        return response()->json(['error' => 'Section not found'], 404);
    }

    // Generate unique_name
    $uniqueName = uniqid(rand(), true);
    if (LandingPageSection::where('unique_name', $uniqueName)->where('id', '!=', $id)->exists()) {
        return response()->json(['error' => 'Section with this name already exists. Please choose a different name.'], 422);
    }

    // Handle image upload
    if ($request->hasFile('image')) {
        \Log::info('Image file exists in the request.');
        
        // Delete the old image if needed
        if ($section->image) {
            $deleted = Storage::delete($section->image);
            \Log::info('Old image deletion: ' . ($deleted ? 'Success' : 'Failed'));
        }

        // Store the new image
        $path = $request->file('image')->store('images/sections', 'public');
        \Log::info("Image stored at: $path");
        
        // Update the section image
        $section->image = $path;
    } else {
        \Log::info('No image file found in the request.');
    }

    // Update other fields
    $section->update(array_merge($request->except('image'), ['unique_name' => $uniqueName]));

    return response()->json($section, 200);
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096', // Limit size and types
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Generate unique_name
        // $uniqueName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $request->title)));
        $uniqueName = uniqid(rand(), true);
        // Check for uniqueness
        if (LandingPageSection::where('unique_name', $uniqueName)->exists()) {
            return response()->json(['error' => 'Section with this name already exists. Please choose a different name.'], 422);
        }

        // Handle image upload
        $path = $request->file('image')->store('images/sections', 'public'); // Save to storage/app/public/images/sections

        $section = LandingPageSection::create(array_merge($request->all(), [
            'unique_name' => $uniqueName,
            'image' => $path, // Store the path in the database
            'name' => $request->name,
        ]));

        return response()->json($section, 201);
    }

    public function create()
    {
        return view('backend.landing-page.create');
    }

    public function destroy($id)
    {
        $section = LandingPageSection::findOrFail($id);
        // Optionally delete the image file from storage if needed
        if ($section->image) {
            Storage::delete($section->image); // Delete the image file
        }
        $section->delete();

        return response()->json($section, 201);
    }

    // api sections


    public function getSections()
    {
        return response()->json(LandingPageSection::paginate(25));
    }

    public function getSectionByUniqueName($uniqueName)
    {
        $section = LandingPageSection::where('unique_name', $uniqueName)->first();

        if ($section) {
            return response()->json($section);
        }

        return response()->json(['message' => 'Section not found'], 404);
    }


}
