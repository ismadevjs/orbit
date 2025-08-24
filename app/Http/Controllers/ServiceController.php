<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class ServiceController extends Controller
{
    public function index()
    {
        return view('backend.services.services');
    }

    public function update(Request $request)
    {
        $service = Service::findOrFail($request->id);

        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('services', 'public');

            $data['image'] = $imagePath;
        }

        $data['name'] = $request->name;
        $data['body'] = $request->body;


        $service->update($data);

        return back()->withSuccess('Service updated successfully!');
    }

    public function store(Request $request)
    {


        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg, webp|max:8192', // Validate image
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('services', 'public'); // Save image in 'public/services'
        }

        Service::create([
            'name' => $request->input('name'),
            'body' => $request->input('body'),
            'image' => $imagePath,
        ]);

        return back()->withSuccess('Service created successfully!');
    }

    public function destroy($serviceId)
    {
        $service = Service::findOrFail($serviceId);
        $service->delete();

        return response()->json(['success' => true, 'message' => 'Service deleted successfully!']);
    }

    // API section for DataTables
    public function apiIndex()
    {
        $services = Service::select(['id', 'name', 'body', 'image'])->get();

        return Datatables::of($services)
            ->addIndexColumn()
            ->make(true);
    }

    public function show($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    public function getServices()
    {
        $services = Service::all(); // Fetch all services

        // Transform the collection to rename 'body' to 'description'
        $services->transform(function ($service) {
            $service->description = $service->body; // Add new key
            unset($service->body); // Remove old key
            return $service; // Return the modified service
        });
        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $services
        ]);
    }

}
