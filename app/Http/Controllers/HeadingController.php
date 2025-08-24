<?php

namespace App\Http\Controllers;

use App\Models\Heading;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class HeadingController extends Controller
{
    public function index()
    {
        return view('backend.headings.headings'); // Adjust view path
    }

    public function update(Request $request, $id)
    {
        $heading = Heading::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:4096',
            'slug' => 'nullable|string',
            'button_name' => 'nullable|string',
            'button_url' => 'nullable|string',
        ]);

        $iconPath = $heading->image;

        if ($request->hasFile('image')) {
            if ($iconPath) {
                Storage::disk('public')->delete($iconPath);
            }
            $iconPath = $request->file('image')->store('headings', 'public');
        }

        $uniqueName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $request->slug)));
        $heading->update([
            'slug' => $uniqueName,
            'title' => $request->title,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $iconPath,
            'button_name' => $request->button_name,
            'button_url' => $request->button_url,
        ]);

        return back()->with('success', 'Heading updated successfully!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|file|image|max:8192',
            'slug' => 'nullable|string',
            'button_name' => 'nullable|string',
            'button_url' => 'nullable|string',
        ]);

        $iconPath = null;

        if ($request->hasFile('image')) {
            $iconPath = $request->file('image')->store('headings', 'public');
        }


        $uniqueName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $request->slug)));

        Heading::create([
            'title' => $request->title,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $iconPath,
            'slug' => $uniqueName,
            'button_name' => $request->button_name,
            'button_url' => $request->button_url,
        ]);

        return back()->with('success', 'Heading added successfully!');
    }

    public function destroy($id)
    {
        $heading = Heading::findOrFail($id);

        if ($heading->icon) {
            Storage::disk('public')->delete($heading->icon);
        }

        $heading->delete();

        return response()->json(['success' => true, 'message' => 'Heading deleted successfully!']);
    }

    public function apiIndex()
    {
        $headings = Heading::query();
        return DataTables::of($headings)
            ->addIndexColumn()
            ->rawColumns(['image'])
            ->make(true);

    }

    public function show($id)
    {
        $heading = Heading::findOrFail($id);
        return response()->json($heading);
    }
}
