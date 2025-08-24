<?php

namespace App\Http\Controllers;

use App\Models\Sound;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class SoundController extends Controller
{
    public function index()
    {
        return view('backend.sounds.sounds');
    }

    public function show($id)
    {
        $sound = Sound::findOrFail($id);
        return response()->json($sound);
    }

    public function destroy($id)
    {
        $sound = Sound::find($id);
        if ($sound && $sound->file) {
            Storage::disk('public')->delete($sound->file);
        }
        $sound->delete();
        return response()->json(['success' => 'Sound link deleted successfully.']);
    }

    public function update(Request $request, $id)
    {
        $sound = Sound::findOrFail($id); // Ensure the record exists.

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'file' => 'nullable|file|mimes:mp3,wav,ogg,aac,m4a|max:8192', // Add the desired audio MIME types here
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $data = $request->only(['name', 'file']);

        // Check if a new file is being uploaded.
        if ($request->hasFile('file')) {
            // Delete the old file if it exists.
            if ($sound->file) {
                Storage::disk('public')->delete($sound->file);
            }

            // Store the new file.
            $data['file'] = $request->file('file')->store('sounds', 'public');
        }

        // Update the sound link with the new data.
        $sound->update($data);

        return response()->json(['success' => 'Sound link updated successfully.']);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'file' => 'nullable|file|mimes:mp3,wav,ogg,aac,m4a|max:8192', // Add the desired audio MIME types here
        ]);


        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $data = $request->all();
        $data['user_id'] = $request->user()->id;
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('sounds', 'public');
        }

        Sound::create($data);

        return response()->json(['success' => 'Sound link saved successfully.']);
    }

    public function apiIndex()
    {
        $sounds = Sound::select(['id', 'name', 'file']);

        return DataTables::of($sounds)
            ->addIndexColumn()
            ->make(true);
    }

    public function getSounds()
    {
        $sounds = Sound::all();
        return response()->json($sounds);

    }
}
