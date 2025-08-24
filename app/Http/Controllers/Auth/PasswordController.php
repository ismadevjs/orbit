<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use Illuminate\Support\Facades\Log;
class PasswordController extends Controller
{

    public function user_update(Request $request)
    {


        Log::info('Password update request received', [
            'user_id' => $request->user()->id ?? 'unknown',
            'request_data' => $request->except(['current_password', 'password', 'password_confirmation'])
        ]);

        try {
            $user = $request->user();

            // Validate input
            $validated = $request->validate([
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            Log::info('Validation passed for password update', ['user_id' => $user->id]);

            // Check current password
            if (!Hash::check($validated['current_password'], $user->password)) {
                Log::warning('Current password verification failed', ['user_id' => $user->id]);

                throw ValidationException::withMessages([
                    'current_password' => ['The current password is incorrect.'],
                ]);
            }

            // Update password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);

            Log::info('Password updated successfully', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully',
            ], 200);

        } catch (ValidationException $e) {
            Log::error('Validation failed for password update', [
                'user_id' => $request->user()->id ?? 'unknown',
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Unexpected error during password update', [
                'user_id' => $request->user()->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
            ], 500);
        }
    }



public function update(Request $request): RedirectResponse
    {
        // Log: Entering the update method
        Log::info('Entering update method for password change', ['user_id' => $request->user()->id ?? 'unknown']);

        // Log: Starting validation for password update
        Log::info('Validating password update request data');
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        // Log: Validation passed
        Log::info('Password update request data validated successfully');

        // Log: Updating user password
        Log::info('Updating password for user', ['user_id' => $request->user()->id]);
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        // Log: Password updated
        Log::info('User password updated successfully', ['user_id' => $request->user()->id]);

        // Log: Preparing redirect response
        Log::info('Redirecting back with status: password-updated');
        return back()->with('status', 'password-updated');
    }




}
