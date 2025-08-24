<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class FaqController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return view('backend.faqs.faqs', compact( 'posts'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required',
            'order' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        // Ensure unique `order` value
        $order = $request->order;
        while (Faq::where('order', $order)->exists()) {
            $order++; // Increment to the next available order
        }

        $data = [
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $order,
            'category_id' => $request->category_id,
        ];

        $faq = Faq::create($data);

        return response()->json(['success' => 'FAQ saved successfully.', 'faq' => $faq]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|string',
            'answer' => 'required',
            'order' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $faq = Faq::findOrFail($id);

        // Ensure unique `order` value
        $order = $request->order;
        if ($faq->order != $order) { // Only check if the order has changed
            while (Faq::where('order', $order)->exists()) {
                $order++; // Increment to the next available order
            }
        }

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $order,
            'category_id' => $request->category_id,
        ]);

        return response()->json(['success' => 'FAQ updated successfully.', 'faq' => $faq]);
    }


    public function show($id)
    {
        $faq = Faq::find($id);
        return response()->json($faq);
    }

    public function destroy($id)
    {
        Faq::destroy($id);
        return response()->json(['success' => 'FAQ deleted successfully.']);
    }

    public function apiIndex()
    {
        // Eager-load the 'category' relationship
        $faqs = Faq::with('category')->select(['id', 'question', 'answer', 'category_id']);

        return DataTables::of($faqs)
            ->addIndexColumn()
            ->addColumn('category_id', function ($faq) {
                return $faq->category ? $faq->category->name : 'N/A';
            })
            ->make(true);
    }
}
