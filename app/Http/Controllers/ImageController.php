<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class ImageController extends Controller
{
    public function index()
    {
        return view('backend.sections.images'); // Adjust view path
    }

    public function update(Request $request, $id)
    {
        $image = Image::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'image' => 'nullable|file|image|max:8192',
        ]);

        $imagePath = $image->image;

        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('images', 'public');
        }

        $image->update([
            'name' => $request->name,
            'code' => $request->code,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Image updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'image' => 'nullable|file|image|max:8192', // Validate uploaded image
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
        }

        Image::create([
            'name' => $request->name,
            'code' => $request->code,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Image added successfully!');
    }

    public function destroy($id)
    {
        $image = Image::findOrFail($id);

        if ($image->image) {
            Storage::disk('public')->delete($image->image);
        }

        $image->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully!']);
    }

    public function apiIndex()
    {
        $images = Image::query();
        return DataTables::of($images)
            ->addIndexColumn()
            ->rawColumns(['image'])
            ->make(true);
    }

    public function show($id)
    {
        $image = Image::findOrFail($id);
        return response()->json($image);
    }
}
