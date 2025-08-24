<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AchievementController extends Controller
{
    public function index()
    {
        return view('backend.achievements.achievements'); // Adjust the view name accordingly
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        Achievement::create([
            'name' => $request->input('name'),
            'value' => $request->input('value'),
        ]);

        return back()->withSuccess('Achievement created successfully!');
    }

    public function update(Request $request, $id)
    {
        $achievement = Achievement::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $achievement->update([
            'name' => $request->input('name'),
            'value' => $request->input('value'),
        ]);

        return back()->withSuccess('Achievement updated successfully!');
    }

    public function destroy($id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievement->delete();

        return response()->json(['success' => true, 'message' => 'Achievement deleted successfully!']);
    }

    // API section for DataTables
    public function apiIndex(Request $request)
    {
        $achievements = Achievement::query();

        return DataTables::of($achievements)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $achievement = Achievement::findOrFail($id);
        return response()->json($achievement);
    }
}
