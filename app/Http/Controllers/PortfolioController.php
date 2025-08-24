<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('backend.portfolios.portfolios');
    }

    public function apiIndex()
    {
        return DataTables::of(Portfolio::query())
            ->addIndexColumn()
            ->addColumn('category_id', function ($row) {
                return $row->category ? $row->category->name : 'N/A';
            })
            ->make(true);
    }

    public function show($id)
    {
        return response()->json(Portfolio::findOrFail($id));
    }

    public function update(Request $request, $id)
    {

        $portfolio = Portfolio::findOrFail($id);
        $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'budget' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        $portfolio->category_id = $request->input('category_id');
        $portfolio->title = $request->title;
        $portfolio->content = $request->input('content');
        $portfolio->budget = $request->budget;
        $portfolio->client = $request->input('client');
        $portfolio->date = $request->input('date');
        if ($request->hasFile('image')) {
            $portfolio->image = $request->file('image')->store('images', 'public');
        }

        $portfolio->save();

        return response()->json($portfolio, 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'category_id' => 'required',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'budget' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        $portfolio = new Portfolio();
        $portfolio->category_id = $request->input('category_id');
        $portfolio->title = $request->title;
        $portfolio->content = $request->input('content');
        $portfolio->budget = $request->budget;
        $portfolio->date = $request->input('date');
        $portfolio->client = $request->input('client');

        if ($request->hasFile('image')) {
            $portfolio->image = $request->file('image')->store('images', 'public');
        }

        $portfolio->save();

        return response()->json($portfolio, 201);
    }

    public function destroy($id)
    {
        Portfolio::destroy($id);
        return response()->json(null, 204);
    }
}
