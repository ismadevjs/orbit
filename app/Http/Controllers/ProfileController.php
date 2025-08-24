<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();
        $countries = json_decode(File::get(public_path('countries.json')));
        return view('backend.profile.profile', compact('user', 'countries'));
    }

   public function update(Request $request)
{
    $user = Auth::user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => [
            'required',
            'email',
            'max:255',
            \Illuminate\Validation\Rule::unique('users')->ignore($user->id),
        ],
 'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:8192',
        'phone' => 'nullable|string|max:255',
        'whatsapp' => 'nullable|string|max:255',
    ]);

    // Update user details
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->phone = $request->input('phone');
    $user->about = $request->input('about');
    $user->address = $request->input('address');
    $user->gender = $request->input('gender');
    $user->country = $request->input('country');
    $user->date_of_birth = $request->input('date_of_birth');
    $user->whatsapp = $request->input('whatsapp');

    // Handle avatar upload
    if ($request->hasFile('avatar')) {
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
    }

    $user->save();

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'user' => $user
        ]);
    }

    return redirect()->back()->with('success', 'Profile updated successfully!');
}

public function changePassword(Request $request)
{
    $request->validate([
        'password' => 'required|string',
        'password-new' => 'required|string|min:8|confirmed',
    ]);

    $user = Auth::user();

    if (!Hash::check($request->input('password'), $user->password)) {
        if ($request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect.'
            ], 422);
        }

        return redirect()->back()->withErrors(['password' => 'Current password is incorrect.']);
    }

    $user->password = Hash::make($request->input('password-new'));
    $user->save();

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully!'
        ]);
    }

    return redirect()->back()->with('success', 'Password changed successfully!');
}


public function updateAvatar(Request $request)
{
    // Validate the request
    $request->validate([
        'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:8192',
        'user_id' => 'required|exists:users,id'
    ]);

    // Get the user - fix the where clause
    $user = User::where('id', $request->user_id)->first();

    // Or better yet, use find:
    // $user = User::find($request->user_id);

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'User not found'
        ], 404);
    }

    // Store the new avatar using Laravel Storage
    if ($request->hasFile('avatar')) {
        // Delete old avatar if exists
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $path;
        $user->save();
    }

    return response()->json([
        'success' => true,
        'message' => 'Avatar updated successfully!',
        'avatar' => Storage::disk('public')->url($user->avatar),
        'user' => $user
    ]);
}

}
