<?php

namespace App\Http\Controllers;

use App\Models\Aksam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class AksamController extends Controller
{
    public function index()
    {
        $countries = json_decode(File::get(public_path('countries.json')));
        return view('backend.aksams.aksams', compact('countries'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // Create a new aksam
        $aksam = Aksam::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,

        ]);

        return response()->json(['success' => true, 'message' => 'Aksame created successfully!', 'aksam' => $aksam]);
    }


    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string',
            'description' => 'nullable|string',
        ]);


        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Find the aksam by ID
        $aksam = Aksam::find($id);

        // Check if the aksam exists
        if (!$aksam) {
            return response()->json(['error' => 'Aksame not found'], 404);
        }


        // Update the aksam
        $aksam->update([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'description' => $request->description,

        ]);

        return response()->json(['success' => true, 'message' => 'Aksame updated successfully!', 'aksam' => $aksam]);
    }




    public function destroy($id)
    {
        $aksam = Aksam::findOrFail($id);
        $aksam->delete();

        return response()->json(['success' => true, 'message' => 'Aksam deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(Aksam::query())
            ->addIndexColumn()
            ->addColumn('user_id', function ($row) {
                return $row->user ? $row->user->name : 'N/A';
            })
            ->make(true);
    }

    public function show($id)
    {
        $aksam = Aksam::findOrFail($id);
        return response()->json($aksam);
    }
}
