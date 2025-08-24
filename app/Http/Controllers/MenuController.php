<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function index()
    {
        // Pass all menus to the view for parent selection
        $menus = Menu::all();
        return view('backend.menus.menus', compact('menus'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',

            'parent_id' => 'nullable|exists:menus,id', // Validate parent_id if provided
        ]);

        // Custom validation for self-parenting
        if ($request->parent_id == null) {
            // If parent_id is null, no need to validate self-parenting
        } elseif ($request->parent_id == $request->id) {
            return response()->json(['errors' => ['parent_id' => 'A menu cannot be its own parent.']], 422);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $menu = Menu::create($request->only('name', 'slug', 'description', 'parent_id'));

        return response()->json($menu, 201);
    }

    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:menus,id', // Validate parent_id if provided
        ]);

        // Custom validation for self-parenting
        if ($request->parent_id == null) {
            // If parent_id is null, no need to validate self-parenting
        } elseif ($request->parent_id == $menu->id) {
            return response()->json(['errors' => ['parent_id' => 'A menu cannot be its own parent.']], 422);
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $menu->update($request->only('name', 'slug', 'description', 'parent_id'));

        return response()->json($menu);
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();

        return response()->json(['message' => 'Menu deleted successfully.']);
    }

    public function show($id)
    {
        // Find the menu by ID, or fail with a 404
        $menu = Menu::findOrFail($id);
        return response()->json($menu);
    }

    public function apiIndex()
    {
        $menus = Menu::with('children')->get();

        return DataTables::of($menus)
            ->addIndexColumn()
            ->make(true);
    }
}
