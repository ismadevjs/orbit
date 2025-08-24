<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Page;
use App\Models\Portfolio;
use App\Models\Post;
use App\Models\Project;
use File;
use Illuminate\Http\Request;

class FrontEndController extends Controller
{
    public function index()
    {
        $countries = json_decode(File::get(public_path('countries.json')), true);
        return view('frontend.index', compact('countries'));
    }

    public function about()
    {
        return view('frontend.about.about');
    }

    public function become() {
        return view('frontend.become.become');
    }

    public function page($slug)
    {
        $page = Page::where('unique_name', $slug)->first();
        if (!$page) abort(404);
        return view('frontend.pages.pages', compact('page'));
    }

    public function members()
    {
        $members = Member::paginate(15);
        return view('frontend.members.members', compact('members'));
    }

    public function membersId($id)
    {
        $member = Member::where('id', $id)->first();
        if (!$member) abort(404);
        return view('frontend.members.details', compact('member'));

    }

    public function properties()
    {
        $properties = Project::paginate(20);
        return view('frontend.properties.properties', compact('properties'));
    }

    public function propertiesId($id)
    {
        $project = Project::where('id', $id)->first();
        if (!$project) abort(404);
        return view('frontend.properties.details', compact('project'));
    }

    public function search(Request $request)
    {
        $params = $request->query('params');

        // Search for projects by name
        $properties = Project::where('name', 'LIKE', "%{$params}%")->paginate(20);

        return view('frontend.properties.search', compact('properties'));
    }


    public function contact()
    {
        $countries = json_decode(File::get(public_path('countries.json')), true);
        return view('frontend.contact.contact', compact('countries'));
    }


    

    public function blog()
    {

        return view('frontend.blog.blog');
    }

    public function posts($id)
    {
        $post = Post::where('id', $id)->first();
        return view('frontend.blog.posts', compact('post'));
    }

    public function filter(Request $request)
    {


        $query = Post::query();

        // Filter by search term
        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%')
                ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        // Filter by tags (from URL parameter)
        if ($request->has('tags') && !empty($request->tags)) {
            $tags = explode(',', $request->tags);  // tags passed as a comma-separated string

            // Trim whitespace around the tags
            $tags = array_map('trim', $tags);

            // Filter posts where the 'tags' field contains any of the provided tags
            $query->where(function ($query) use ($tags) {
                foreach ($tags as $tag) {
                    $query->orWhere('tags', 'like', '%' . $tag . '%');
                }
            });
        }

        // Filter by category (from URL parameter)
        if ($request->has('category') && !empty($request->category)) {
            $category = $request->category;
            $query->where('category_id', $category); // Assuming category_id exists in BlogPost model
        }

        // Get the filtered blog posts
        $posts = $query->paginate(25);

        return view('frontend.blog.blog', compact('posts'));
    }

    public function services() {
        return view('frontend.services.services');
    }

    public function projects() {
        $projects = Portfolio::paginate(25);
        return view('frontend.projects.projects', compact('projects'));
    }

    public function projectsId($id) {
        $project = Portfolio::where('id', $id)->first();
        if (!$project) abort(404);
        return view('frontend.projects.details', compact('project'));
    }

    public function thankyou() {
        return view('frontend.thankyou');
    }

}
