<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\OtpNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OtpController extends Controller
{
    public function show()
    {
        if (!session('otp_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required|numeric|digits:6']);

        $user = User::find(session('otp_user_id'));

        if (!$user || $user->otp !== $request->otp || now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        // Clear OTP and log in
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        $token = session('pending_token');
        $request->session()->put('token', $token); // Store token for API usage
        $request->session()->forget('pending_token'); // Clear temporary token


        Auth::login($user);
        session()->forget('otp_user_id');

        return redirect()->intended(route('backend.index')); // Adjust to your app’s default route
    }

    public function resend(Request $request)
    {
        $user = User::find(session('otp_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        // Generate a new 6-digit OTP
        $otp = rand(100000, 999999);
        $user->otp = $otp;
        $user->otp_expires_at = now()->addMinutes(10);
        $user->save();

        // Send the new OTP
        $user->notify(new OtpNotification($otp, $user));


        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}