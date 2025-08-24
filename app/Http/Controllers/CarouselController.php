<?php

namespace App\Http\Controllers;

use App\Models\Carousel;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class CarouselController extends Controller
{
    // Display the carousel management page
    public function index()
    {
        $carousels = Carousel::paginate(15);
        $projects = Project::get();
        return view('backend.carousel.carousel', compact('carousels', 'projects'));
    }

    // Store a new carousel
    public function store(Request $request)
    {
        \Log::info('Store Request:', $request->all());

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:6144',
            'buttons' => 'nullable|array',
            'buttons.*.name' => 'nullable|string|max:255',
            'buttons.*.link' => 'nullable|url|max:255',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $data = $request->only(['title', 'subtitle', 'body']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('carousels', 'public');
        }



        $data['buttons'] = $request->has('buttons') && !empty($request->input('buttons'))
            ? $request->input('buttons')
            : null;

        Carousel::create($data);
        return back()->withSuccess('Carousel created successfully!');
    }

    public function update(Request $request)
{


    $validator = Validator::make($request->all(), [
        'id' => 'required|exists:carousels,id',
        'title' => 'required|string|max:255',
        'subtitle' => 'nullable|string',
        'body' => 'nullable|string',
        'video_text' => 'nullable|string',
        'video_id' => 'nullable|string',
        'image' => 'nullable|image|max:8192',
        'buttons' => 'nullable|array',
        'buttons.*.name' => 'nullable|string|max:255',
        'buttons.*.link' => 'nullable|url|max:255',
    ]);

    if ($validator->fails()) {
        return back()->withErrors($validator->errors())->withInput();
    }

    $carousel = Carousel::findOrFail($request->id);

    $data = $request->only(['title', 'subtitle', 'body', 'video_text', 'video_id']);

    // Handle Image Upload
    if ($request->hasFile('image')) {
        if ($carousel->image) {
            Storage::disk('public')->delete($carousel->image);
        }
        $data['image'] = $request->file('image')->store('carousels', 'public');
    } else {
        $data['image'] = $carousel->image;
    }

    // Handle buttons - store as array directly
    $data['buttons'] = $request->has('buttons') && !empty($request->input('buttons'))
        ? $request->input('buttons')
        : null;


    $carousel->update($data);

    return redirect()->back()->with('success', 'Carousel updated successfully!');
}


    // Delete a carousel

    public function destroy($carouselId)
    {
        $carousel = Carousel::findOrFail($carouselId);

        // Delete image from storage
        if ($carousel->image) {
            Storage::disk('public')->delete($carousel->image);
        }

        // Delete carousel
        $carousel->delete();

        // Return a JSON response for AJAX
        return back()->with('success', 'Carousel deleted successfully!');

    }

    // API: Get paginated carousels
    public function apiIndex(Request $request)
    {
        // Get the paginated carousels
        $carousels = Carousel::with('project')->firstOrFail();

        // Return JSON response
        return response()->json($carousels);
    }
}
