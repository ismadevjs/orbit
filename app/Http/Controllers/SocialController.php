<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SocialController extends Controller
{
    public function index()
    {
        return view('backend.socials.socials');
    }

    public function show($id)
    {
        $social = Social::findOrFail($id);
        return response()->json($social);
    }

    public function destroy($id)
    {
        $social = Social::find($id);
        if ($social && $social->image) {
            Storage::disk('public')->delete($social->image);
        }
        $social->delete();
        return response()->json(['success' => 'Social link deleted successfully.']);
    }

    public function update(Request $request, $id)
    {
        $social = Social::findOrFail($id); // Ensure the record exists.

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $data = $request->only(['name', 'link']); // Only necessary fields.

        // Check if a new image is being uploaded.
        if ($request->hasFile('image')) {
            // Delete the old image if it exists.
            if ($social->image) {
                Storage::disk('public')->delete($social->image);
            }

            // Store the new image.
            $data['image'] = $request->file('image')->store('socials', 'public');
        }

        // Update the social link with the new data.
        $social->update($data);

        return response()->json(['success' => 'Social link updated successfully.']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'link' => 'required|url',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('socials', 'public');
        }

        Social::create($data);

        return response()->json(['success' => 'Social link saved successfully.']);
    }

    public function apiIndex()
    {
        $socials = Social::select(['id', 'name', 'link', 'image']);

        return DataTables::of($socials)
            ->addIndexColumn()
            ->make(true);
    }

    public function getSocials()
    {
        $socials = Social::all();
        return response()->json($socials);

    }
}
