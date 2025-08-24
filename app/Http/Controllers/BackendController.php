<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Carousel;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Facility;
use App\Models\Lead;
use App\Models\Post;
use App\Models\Project;
use App\Models\Review;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\Video;
use Artisan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class BackendController extends Controller
{



    // clear cache

    public function cacheClear()
    {
        Artisan::call('optimize:clear');
        return back()->withSuccess('تم مسح الكاش بنجاح.');
    }


    public function index()
    {

        if (Auth::user()->hasRole('investor'))
            return redirect()->route('investor.investor_analytics.index');

        $projects = Project::count();
        $areas = Area::count();
        $facilities = Facility::count();
        $posts = Post::count();
        $reviews = Review::count();
        $testimonials = Testimonial::count();
        $videos = Video::count();
        $users = User::count();
        $leads = Lead::count();
        $categories = Category::count();

        // Monthly statistics
        $usersCount = User::whereMonth('created_at', now()->month)->count();
        $contactRequestsCount = Contact::whereMonth('created_at', now()->month)->count();
        $postsCount = Post::whereMonth('created_at', now()->month)->count();
        $reviewsCount = Review::whereMonth('created_at', now()->month)->count();

        // If you have some data for projects, you might want to define it here
        $projectsData = Project::selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count');

        // Ensure data length is 4 for weekly display
        $projectsData = $projectsData->pad(4, 0);

        // Similar data fetching for leads, reviews, and areas can be done here
        $leadsData = Lead::selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count')
            ->pad(4, 0);

        $reviewsData = Review::selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count')
            ->pad(4, 0);

        $areasData = Area::selectRaw('COUNT(*) as count, DATE_FORMAT(created_at, "%Y-%m-%d") as date')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count')
            ->pad(4, 0);

        return view('backend.index', compact(
            'projects',
            'areas',
            'facilities',
            'posts',
            'reviews',
            'testimonials',
            'videos',
            'users',
            'leads',
            'categories',
            'usersCount',
            'postsCount',
            'reviewsCount',
            'contactRequestsCount',
            'projectsData',
            'leadsData',
            'reviewsData',
            'areasData' // Add the new variables here
        ));
    }

    public function carousel()
    {
        $carousels = Carousel::paginate(15); // Fetching carousel items with pagination
        return view('backend.carousel.carousel', compact('carousels'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        // Ensure search term is trimmed and lowercased for better matching
        $searchTerm = strtolower(trim($searchTerm));

        // Retrieve all routes
        $routes = Route::getRoutes();

        // Filter routes that contain '.index', do not contain 'api.index', and match the search term
        $results = collect($routes)->filter(function ($route) use ($searchTerm) {
            $routeName = strtolower($route->getName() ?? '');
            return strpos($routeName, '.index') !== false &&
                strpos($routeName, 'api.index') === false &&  // Exclude 'api.index'
                strpos($routeName, $searchTerm) !== false;  // Match search term
        });

        // Get the first matching route
        $firstResult = $results->first();

        if ($firstResult) {
            // Redirect to the route's URI
            return redirect()->route($firstResult->getName());
        }

        // If no matching route found, you may want to redirect to a default route or return an error
        return redirect()->route('default.route'); // Change 'default.route' to your desired fallback route
    }

    public function update(Request $request)
    {
        return $request;
    }

}
