<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class CategoryController extends Controller
{
    public function index()
    {
        return view('backend.categories.categories');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_id' => 'nullable|integer|exists:types,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category = Category::create($request->only('name', 'description', 'type_id'));

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type_id' => 'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $category->update($request->only('name', 'description', 'type_id'));

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully.']);
    }


    public function show($id)
    {
        // Find the category by ID, or fail with a 404 response
        $category = Category::findOrFail($id);

        // Return the category as JSON
        return response()->json($category);
    }


    public function apiIndex()
    {


        $categories = Category::query();

        return DataTables::of($categories)
            ->addIndexColumn()
            ->addColumn('type_id', function ($category) {
                return $category->type ? $category->type->name : 'N/A';
            })
            ->addColumn('actions', function ($category) {
                return '<button class="btn btn-warning btn-sm edit" data-id="' . $category->id . '">Edit</button>
                        <button class="btn btn-danger btn-sm delete" data-id="' . $category->id . '">Delete</button>';
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
