<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PostController extends Controller
{
    public function index()
    {
        return view('backend.posts.posts');
    }

    public function apiIndex()
    {
        return DataTables::of(Post::query())
            ->addIndexColumn()
            ->addColumn('category_id', function ($row) {
                return $row->category ? $row->category->name : 'N/A';
            })
            ->make(true);
    }

    public function show($id)
    {
        return response()->json(Post::findOrFail($id));
    }

    public function update(Request $request, $id)
    {

        $post = Post::findOrFail($id);
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        $post->category_id = $request->input('category_id');
        $post->title = $request->title;
        $post->content = $request->input('content');
        $post->author = $request->author;
        $post->tags = $request->input('tags');

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return response()->json($post, 200);
    }

    public function store(Request $request)
    {

        \Log::info($request->all());
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'author' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        $post = new Post();
        $post->category_id = $request->input('category_id');
        $post->title = $request->title;
        $post->content = $request->input('content');
        $post->author = $request->author;
        $post->tags = $request->input('tags');

        if ($request->hasFile('image')) {
            $post->image = $request->file('image')->store('images', 'public');
        }

        $post->save();

        return response()->json($post, 201);
    }

    public function destroy($id)
    {
        Post::destroy($id);
        return response()->json(null, 204);
    }
}

