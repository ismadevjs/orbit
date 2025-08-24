<?php

// app/Http/Controllers/TagController.php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{
    public function index()
    {
        return view('backend.tags.tags');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tag = Tag::create($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'Tag created successfully!', 'tag' => $tag]);
    }

    public function update(Request $request, $id)
    {
        $tag = Tag::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $tag->update($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'Tag updated successfully!', 'tag' => $tag]);
    }

    public function destroy($id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return response()->json(['success' => true, 'message' => 'Tag deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(Tag::query())
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $tag = Tag::findOrFail($id);
        return response()->json($tag);
    }
}
