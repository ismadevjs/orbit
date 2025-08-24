<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

// Ensure this model exists

class FacilityController extends Controller
{
    public function index()
    {
        return view('backend.facilities.facilities'); // Adjust the view name accordingly
    }

    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        Facility::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'icon' => $request->input('icon'),
        ]);

        return back()->withSuccess('Facility created successfully!');
    }

    public function update(Request $request, $id)
    {
        $facility = Facility::findOrFail($id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        $facility->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'icon' => $request->input('icon'),
        ]);

        return back()->withSuccess('Facility updated successfully!');
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();

        return response()->json(['success' => true, 'message' => 'Facility deleted successfully!']);
    }

    // API section for DataTables
    public function apiIndex(Request $request)
    {
        $facilities = Facility::query();

        return DataTables::of($facilities)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $facility = Facility::findOrFail($id);
        return response()->json($facility);
    }
}
