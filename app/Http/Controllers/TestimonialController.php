<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TestimonialController extends Controller
{
     public function index()
     {
         $testimonials = Testimonial::whereNotNull('image')->paginate(10);
         return view('backend.testimonials.testimonials', compact('testimonials'));
     }

     public function store(Request $request)
     {
         // Validation
         $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|nullable|string|max:255',
            'message' => 'sometimes|required|string',
            'image' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
         ]);

         if ($validator->fails()) {
             return back()->withErrors($validator->errors());
         }

         // Handle File Upload
         if ($request->hasFile('image')) {
             $imagePath = $request->file('image')->store('testimonials', 'public');
         }

         Testimonial::create([
             'name' => $request->input('name'),
             'image' => $imagePath,
             'position'=> $request->input('position'),
             'message'=> $request->input('message'),
         ]);

         return back()->withSuccess('Testimonial created successfully!');
     }




     public function update(Request $request)
     {
         $testimonial = Testimonial::findOrFail($request->id);

         // Validation
         $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|nullable|string|max:255',
            'message' => 'sometimes|required|string',
         ]);

         if ($validator->fails()) {
             return back()->withErrors($validator->errors());
         }

         // Handle File Upload
         if ($request->hasFile('image')) {
             // Delete the old image
             if ($testimonial->image) {
                 Storage::disk('public')->delete($testimonial->image);
             }

             // Store new image
             $imagePath = $request->file('image')->store('testimonials', 'public');
             $testimonial->image = $imagePath;
         }


         $testimonial->update([
             'name' => $request->input('name'),
             'position'=> $request->input('position'),
             'message'=> $request->input('message'),
         ]);
         return back()->withSuccess('Testimonial updated successfully!');
     }


     public function destroy($testimonialId)
     {
         $testimonial = Testimonial::findOrFail($testimonialId);

         // Delete image from storage
         if ($testimonial->image) {
             Storage::disk('public')->delete($testimonial->image);
         }


         $testimonial->delete();

         // Return a JSON response for AJAX
         return response()->json([
             'success' => true,
             'message' => 'Testimonial deleted successfully!'
         ]);
     }



     // video section

     public function indexVideo()
     {
         $testimonials = Testimonial::whereNotNull('video')->paginate(10);
         return view('backend.testimonials.testimonialsVideo', compact('testimonials'));
     }

     public function storeVideo(Request $request)
     {
         // Validation
         $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|nullable|string|max:255',
            'message' => 'sometimes|required|string',
            'video' =>  'sometimes|nullable|mimes:mp4,avi,mov|max:10240'
         ]);

         if ($validator->fails()) {
             return back()->withErrors($validator->errors());
         }

         // Handle File Upload
         if ($request->hasFile('video')) {
             $videoPath = $request->file('video')->store('testimonials', 'public');
         }

         Testimonial::create([
             'name' => $request->input('name'),
             'video' => $videoPath,
             'position'=> $request->input('position'),
             'message'=> $request->input('message'),
         ]);

         return back()->withSuccess('Testimonial created successfully!');
     }




     public function updateVideo(Request $request)
     {
         $testimonial = Testimonial::findOrFail($request->id);

         // Validation
         $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'position' => 'sometimes|nullable|string|max:255',
            'message' => 'sometimes|required|string',
         ]);

         if ($validator->fails()) {
             return back()->withErrors($validator->errors());
         }

         // Handle File Upload
         if ($request->hasFile('video')) {
             // Delete the old video
             if ($testimonial->video) {
                 Storage::disk('public')->delete($testimonial->video);
             }

             // Store new video
             $videoPath = $request->file('video')->store('testimonials/videos', 'public');
             $testimonial->video = $videoPath;
         }


         $testimonial->update([
             'name' => $request->input('name'),
             'position'=> $request->input('position'),
             'message'=> $request->input('message'),
         ]);
         return back()->withSuccess('Testimonial updated successfully!');
     }


     public function destroyVideo($testimoniaVideolId)
     {
         $testimonial = Testimonial::findOrFail($testimoniaVideolId);

         // Delete video from storage
         if ($testimonial->video) {
             Storage::disk('public')->delete($testimonial->video);
         }


         $testimonial->delete();

         // Return a JSON response for AJAX
         return response()->json([
             'success' => true,
             'message' => 'Testimonial deleted successfully!'
         ]);
     }




     // api sections

     public function apiIndex(Request $request)
     {
         // Get the paginated
         $testimonial = Testimonial::paginate(10);

         // Return JSON response
         return response()->json($testimonial);
     }


     public function apiImage(Request $request)
     {
         // Get the paginated
         $testimonial = Testimonial::whereNotNull('image')->paginate(10);

         // Return JSON response
         return response()->json($testimonial);
     }



     public function apiVideo(Request $request)
     {
         // Get the paginated
         $testimonial = Testimonial::whereNotNull('video')->paginate(10);

         // Return JSON response
         return response()->json($testimonial);
     }
}
