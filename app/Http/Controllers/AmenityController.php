<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class AmenityController extends Controller
{
    public function index()
    {
        return view('backend.amenities.amenities'); // Adjust view path
    }

    public function update(Request $request, $id)
    {
        $amenity = Amenity::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:8192',
        ]);

        $iconPath = $amenity->image;

        if ($request->hasFile('image')) {
            if ($iconPath) {
                Storage::disk('public')->delete($iconPath);
            }
            $iconPath = $request->file('image')->store('amenities', 'public');
        }

        $amenity->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $iconPath,
        ]);

        return back()->with('success', 'Amenity updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:8192', // Validate uploaded image
        ]);

        $iconPath = null;

        if ($request->hasFile('image')) {
            $iconPath = $request->file('image')->store('amenities', 'public');
        }

        Amenity::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $iconPath,
        ]);

        return back()->with('success', 'Amenity added successfully!');
    }

    public function destroy($id)
    {
        $amenity = Amenity::findOrFail($id);

        if ($amenity->icon) {
            Storage::disk('public')->delete($amenity->icon);
        }

        $amenity->delete();

        return response()->json(['success' => true, 'message' => 'Amenity deleted successfully!']);
    }

    public function apiIndex()
    {
        $amenities = Amenity::query();
        return DataTables::of($amenities)
            ->addIndexColumn()
            ->addColumn('icon', function ($amenity) {
                return $amenity->icon ? '<img src="' . asset('/storage/' . $amenity->icon) . '" alt="Icon" class="img-thumbnail" width="50">' : 'No Icon';
            })
            ->rawColumns(['icon'])
            ->make(true);
    }

    public function show($id)
    {
        $amenity = Amenity::findOrFail($id);
        return response()->json($amenity);
    }
}
