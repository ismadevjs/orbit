<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class JobController extends Controller
{
    public function index()
    {
        return view('backend.jobs.jobs');
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

        $job = Job::create($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'Job created successfully!', 'job' => $job]);
    }

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $job->update($request->only('name', 'description'));

        return response()->json(['success' => true, 'message' => 'Job updated successfully!', 'job' => $job]);
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        $job->delete();

        return response()->json(['success' => true, 'message' => 'Job deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(Job::query())
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);
        return response()->json($job);
    }
}
