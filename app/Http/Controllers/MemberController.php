<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class MemberController extends Controller
{
    public function index()
    {
        return view('backend.members.members');
    }

    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [

            'position' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'practice_area' => 'required|string|max:255',
            'projects_done' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }

            $image = $request->file('image');
            $imagePath = $image->store('members', 'public');
            $data['image'] = $imagePath;
        }

        $member->update($data);

        return response()->json(['success' => true, 'message' => 'Member updated successfully!']);
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'position' => 'required|string|max:255',
            'experience' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'practice_area' => 'required|string|max:255',
            'projects_done' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'facebook' => 'nullable|string|max:255',
            'instagram' => 'nullable|string|max:255',
            'youtube' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'linkedin' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('members', 'public');
            $data['image'] = $imagePath;
        }

        Member::create($data);

        return response()->json(['success' => true, 'message' => 'Member created successfully!']);
    }

    public function destroy($id)
    {
        $member = Member::findOrFail($id);

        // Delete associated image if exists
        if ($member->image) {
            Storage::disk('public')->delete($member->image);
        }

        $member->delete();
        return response()->json(['success' => true, 'message' => 'Member deleted successfully!']);
    }

    public function apiIndex()
    {
        $members = Member::query();
        return DataTables::of($members)
            ->addIndexColumn()
            ->addColumn('user_id', function ($row) {
                return $row->user ? $row->user->email : 'N/A';
            })
            ->make(true);
    }

    public function show($id)
    {
        $member = Member::findOrFail($id);
        return response()->json($member);
    }
}
