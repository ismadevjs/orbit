<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class VideoController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Fetch all categories
        return view('backend.videos.videos', compact('categories')); // Pass categories to the view
    }

    public function show($id)
    {
        $video = Video::findOrFail($id);
        return response()->json($video);
    }

    public function update(Request $request)
    {


        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url',
            'category_id' => 'required|exists:categories,id'
        ]);


        $data['title'] = $request->title;
        $data['link'] = $request->link;
        $data['category_id'] = $request->category_id;


        if ($request->hasFile('image')) {
            $video = Video::findOrFail($request->videoId);
            if ($video->image) {
                Storage::disk('public')->delete($video->image);
            }
            $path = $request->file('image')->store('images/videos', 'public');
            $data['image'] = $path;
        }

        Video::where('id', $request->videoId)->update($data);

        return response()->json(['message' => 'Video updated successfully!']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        ]);

        $video = new Video();
        $video->title = $request->title;
        $video->link = $request->link;
        $video->category_id = $request->category_id;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images/videos', 'public');
            $video->image = $path; // Store image path
        }

        $video->save();

        return response()->json(['message' => 'Video created successfully!']);
    }

    public function destroy(Video $video)
    {
        if ($video->image) {
            Storage::disk('public')->delete($video->image);
        }

        $video->delete(); // Delete the video record
        return response()->json(['message' => 'Video deleted successfully!']);
    }

    public function apiIndex()
    {
        return DataTables::of(Video::with('category')->get())
            ->addIndexColumn()
            ->make(true);
    }

    public function getVideos()
    {
        $videos = Video::all();

        // Transform the videos into the desired format
        $formattedVideos = $videos->map(function ($video) {
            return [
                'title' => $video->title,
                'subtitle' => '', // Assuming subtitle is empty as per your example
                'category' => '', // Assuming category is empty as per your example
                'background' => "https://i.ytimg.com/vi/" . $this->extractVideoId($video->link) . "/maxresdefault.jpg",
                'details' => [
                    'title' => $video->title,
                    'id' => $this->extractVideoId($video->link),
                    'preview' => "https://i.ytimg.com/vi/" . $this->extractVideoId($video->link) . "/maxresdefault.jpg",
                    'platform' => 'youtube',
                ],
                'date' => $video->created_at, // Assuming date is null as per your example
            ];
        });

        return response()->json(['data' => $formattedVideos]);
    }

// Helper function to extract video ID from YouTube link
    private function extractVideoId($link)
    {
        parse_str(parse_url($link, PHP_URL_QUERY), $query);
        return $query['v'] ?? ''; // Return the video ID or an empty string if not found
    }

}
