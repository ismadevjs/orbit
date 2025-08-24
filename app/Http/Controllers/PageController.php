<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class PageController extends Controller
{
    public function index()
    {
        return view('backend.pages.pages');
    }

    public function update(Request $request)
    {
        $page = Page::find($request->input('id'));

        $validator = Validator::make($request->all(), [
            'unique_name' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $imagePath = $page->image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('pages', 'public');
        }
        $uniqueName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $request->unique_name)));

        $page->update([
            'unique_name' => $uniqueName,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image' => $imagePath,
        ]);

        return response()->json(['success' => true]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_name' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('pages', 'public');
        }
        $uniqueName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $request->unique_name)));
        Page::create([
            'unique_name' => $uniqueName,
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image' => $imagePath,
        ]);

        return back()->withSuccess('Page created successfully!');
    }

    public function show($id)
    {
        $page = Page::find($id);
        return response()->json($page);
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        if ($page) {
            $page->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 404);
    }

    public function apiIndex()
    {
        $pages = Page::all();
        return DataTables::of($pages)
            ->addIndexColumn()
            ->make(true);
    }
}
