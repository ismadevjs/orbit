<?php

namespace App\Http\Controllers;

use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PopupController extends Controller
{
    public function index()
    {
        return view('backend.popups.popups');
    }

    public function apiIndex()
    {
        $popups = Popup::query();
        return datatables()::of($popups)
            ->addIndexColumn()
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'status' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|integer|min:1|max:10', // New field for popup priority
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('popups', 'public');
        }

        Popup::create($data);

        return response()->json(['success' => true, 'message' => 'Popup created successfully!']);
    }

    public function show($id)
    {
        $popup = Popup::findOrFail($id);
        return response()->json($popup);
    }

    public function update(Request $request, $id)
    {
        $popup = Popup::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
            'status' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'priority' => 'nullable|integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('popups', 'public');
        }

        $popup->update($data);

        return response()->json(['success' => true, 'message' => 'Popup updated successfully!']);
    }

    public function destroy($id)
    {
        $popup = Popup::findOrFail($id);
        $popup->delete();

        return response()->json(['success' => true, 'message' => 'Popup deleted successfully!']);
    }
    public function fetchActive()
    {
        // Get the first popup without any conditions
        $popup = Popup::first();

        // If no popup exists, return a default one
        if (!$popup) {
            $popup = [
                'id' => 0,
                'title' => 'Welcome to Our Site!',
                'description' => 'This is a default popup message.',
                'image' => null,
                'action_url' => '#',
                'action_text' => 'Get Started'
            ];
        } else {
            // Ensure only necessary fields are returned
            $popup = $popup->only(['id', 'title', 'description', 'image', 'action_url', 'action_text']);
        }

        return response()->json($popup);
    }
}