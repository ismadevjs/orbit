<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ReviewController extends Controller
{
    public function index()
    {
        return view('backend.reviews.reviews');
    }

    public function show($id)
    {
        $review = Review::find($id);
        return response()->json($review);
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if ($review && $review->image) {
            Storage::disk('public')->delete($review->image);
        }
        $review->delete();
        return response()->json(['success' => 'Review deleted successfully.']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|string',
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $data = $request->all();
        \Log::info($request->hasFile('image'));
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }

        Review::where('id', $id)->update($data);

        return response()->json(['success' => 'Review saved successfully.']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|string',
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:8192',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('reviews', 'public');
        }

        Review::create($data);

        return response()->json(['success' => 'Review saved successfully.']);
    }

    public function apiIndex()
    {
        $reviews = Review::select(['id', 'name', 'role', 'comment', 'rating', 'image']);

        return DataTables::of($reviews)
            ->addIndexColumn()
            ->make(true);
    }

    public function getReviews()
    {
        $reviews = Review::all();
        return response()->json(['data' => $reviews]);
    }
}
