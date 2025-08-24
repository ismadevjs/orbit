<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Feature;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index()
    {

        $areas = Area::get();
        return view('backend.projects.projects', compact('areas'));
    }

    public function add()
    {
        $areas = Area::get();
        $categories = Category::get();
        $facilities = Facility::get();
        $features = Feature::get();
        return view('backend.projects.add', compact('areas', 'categories', 'facilities', 'features'));
    }

    public function apiIndex(Request $request)
    {
        $projects = Project::where('user_id', Auth::id())->get();
        return DataTables::of($projects)->addIndexColumn()->make(true);
    }

    public function apiGetProjects()
    {
        $projects = Project::paginate(25);
        return response()->json($projects);
    }

    public function edit($id)
    {
        // Retrieve the project by ID
        $project = Project::findOrFail($id);

        // Get the necessary data for the view
        $areas = Area::get();
        $categories = Category::get();
        $facilities = Facility::get();
        $features = Feature::get();

        // Pass the project data along with other data to the view
        return view('backend.projects.edit', compact('project', 'areas', 'categories', 'facilities', 'features'));
    }

    public function update(Request $request)
    {


        // return $request->all();

        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            '_token' => 'required|string',
            'project_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:8192',
            'area' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'address' => 'nullable|string',
            'facilities' => 'nullable|array',
            'facilities.*' => 'string',
            'distances' => 'nullable|array',
            'distances.*' => 'numeric',
            'custom_field_names' => 'required|array',
            'custom_field_names.*' => 'string',
            'custom_field_values' => 'required|array',
            'custom_field_values.*' => 'string',
            'video_links' => 'required|array',
            'video_links.*' => 'url',
            'speciality_video' => 'nullable|url',
            'detailed_video' => 'nullable|string',
            'project_types' => 'required|array',
            'project_types.*' => 'string',
            'surfaces' => 'required|array',
            'surfaces.*' => 'numeric',
            'prices' => 'required|array',
            'prices.*' => 'numeric',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = [
            'name' => $request->project_name,
            'price' => $request->price,
            'description' => $request->description,
            'area' => $request->area,
            'facilities' => json_enrrcode($request->facilities),
            'distances' => $request->distances,
            'custom_field_names' => $request->custom_field_names,
            'custom_field_values' => $request->custom_field_values,
            'video_links' => $request->video_links,
            'speciality_video' => $request->speciality_video,
            'detailed_video' => $request->detailed_video,
            'project_types' => $request->project_types,
            'surfaces' => $request->surfaces,
            'prices' => $request->prices,
        ];

        if ($request->lat) {
            $data['location'] = [
                'lat' => $request->latitude,
                'lng' => $request->longitude,
                'address' => $request->address,
            ];
        }

        // Handle file uploads for update
        if ($request->hasFile('images')) {
            // Retrieve the project
            $project = Project::findOrFail($request->id);

            // Delete existing images from storage
            foreach ($project->images as $image) {
                Storage::disk('public')->delete($image);
            }


            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('projects', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        Project::where('id', $request->id)->update($data);

        return redirect()->route('projects.index')->withSuccess('Project updated successfully');
    }

    public function store(Request $request)
    {


        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|max:8192',
            'area' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'required|string',
            'facilities' => 'required|array',
            'facilities.*' => 'string',
            'distances' => 'required|array',
            'distances.*' => 'numeric',
            'custom_field_names' => 'required|array',
            'custom_field_names.*' => 'string',
            'custom_field_values' => 'required|array',
            'custom_field_values.*' => 'string',
            'video_links' => 'required|array',
            'video_links.*' => 'url',
            'speciality_video' => 'nullable|url',
            'detailed_video' => 'nullable|string',
            'project_types' => 'required|array',
            'project_types.*' => 'string',
            'surfaces' => 'required|array',
            'surfaces.*' => 'numeric',
            'prices' => 'required|array',
            'prices.*' => 'numeric',
        ]);

        // If validation fails, return error response
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        // Prepare data array with all necessary attributes
        $data = [
            'user_id' => Auth::user()->id,
            'name' => $request->project_name,
            'price' => $request->price,
            'description' => $request->description,
            'area' => $request->area,
            'location' => [
                'lat' => $request->latitude,
                'lng' => $request->longitude,
                'address' => $request->address,
            ],
            'facilities' => $request->facilities,
            'distances' => $request->distances,
            'custom_field_names' => $request->custom_field_names,
            'custom_field_values' => $request->custom_field_values,
            'video_links' => $request->video_links,
            'speciality_video' => $request->speciality_video,
            'detailed_video' => $request->detailed_video,
            'project_types' => $request->project_types,
            'surfaces' => $request->surfaces,
            'prices' => $request->prices,
        ];

        if ($request->hasFile('images')) {

            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('projects', 'public');
                $imagePaths[] = $path;
            }
            $data['images'] = $imagePaths;
        }

        // Create project with all attributes
        $project = Project::create($data);

        return redirect()->route('projects.index')->withSuccess('Project Created');
    }


    public function destroy($id)
    {
        // Retrieve the project
        $project = Project::findOrFail($id);

        // Delete images from storage
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image);
        }

        // Delete the project
        Project::destroy($id);
        return response()->json(['success' => 'Project deleted successfully!']);
    }

    // api calls

    public function getThreeProjects()
    {
        $projects = Project::select('id', 'name', 'description', 'images')
            ->limit(3)
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $projects
        ]);
    }

    public function getProjectDetails($id)
    {
        $getProject = Project::with('area')->findOrFail($id);
        $facilities = Facility::whereIn('id', $getProject->facilities)->get();
        $projectDetails = $getProject->toArray();
        unset($projectDetails['facilities']);
        $projectDetails['facilities'] = $facilities;

        return response()->json([
            'status' => true,
            'message' => '',
            'data' => $projectDetails
        ]);
    }


}
