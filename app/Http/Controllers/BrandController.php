<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class BrandController extends Controller
{
    public function index()
    {
        return view('backend.brands.brands');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:8192',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $data = [
            'name' => $request->input('name'),
        ];

        if ($request->hasFile('image')) {
            // Delete the old image
            if ($brand->image) {
                Storage::disk('public')->delete($brand->image);
            }

            // Store the new image
            $data['image'] = $request->file('image')->store('brands', 'public');
        }

        $brand->update($data);

        return response()->json(['success' => true, 'message' => 'Brand updated successfully!']);

    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,ico|max:8192',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $data = [
            'name' => $request->input('name'),
        ];

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('brands', 'public');
        }

        Brand::create($data);

        return response()->json(['success' => true, 'message' => 'Brand created successfully!']);

    }

    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        return response()->json(['success' => true, 'message' => 'Brand deleted successfully!']);
    }

    public function apiIndex(Request $request)
    {
        $brands = Brand::query();

        return DataTables::of($brands)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $brand = Brand::findOrFail($id);
        return response()->json($brand);
    }
}
