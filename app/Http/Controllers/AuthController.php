<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Http\Controllers\Controller;
use App\Models\Employe;
use App\Models\Investor;
use App\Models\ReferralLink;
use App\Models\Wallet;
use App\Rules\CloudflareTurnstile;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    public function register(Request $request)
    {
         $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ];


        // Validate the request with all rules
        $request->validate($rules);

        $emailValidation = validateEmail($request->email, $request);

        if (!$emailValidation['success']) {
            return redirect()->back()->withErrors(['error' => $emailValidation['message']]);

        }

        DB::beginTransaction();

        try {
            $affiliateId = null;


            // Step 1: Check for the referral token
            if ($request->filled('ref')) {
                try {
                    $encryptedToken = base64_decode($request->input('ref'));

                    Log::info('Decoded Referral Token:', ['token' => $encryptedToken]);

                    // Decrypt and decode the referral data
                    $data = json_decode(Crypt::decryptString($encryptedToken), true);

                    Log::info('Decrypted Referral Data:', $data);

                    // Validate referral link in the database
                    $link = ReferralLink::where('encrypted_token', $request->input('ref'))->first();

                    if ($link && !$link->isExpired()) {
                        $affiliateId = $data['referrer_id'];
                        Log::info('Valid Referral Link Found', ['affiliate_id' => $affiliateId]);
                    } else {
                        Log::warning('Referral Link Expired or Invalid', ['ref' => $encryptedToken]);
                    }
                } catch (\Exception $e) {
                    Log::error('Failed to Process Referral Link:', ['error' => $e->getMessage()]);
                }
            }

            // Step 2: Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'affiliate_id' => $affiliateId,
            ]);

            Log::info('User created successfully:', ['user' => $user]);

            // Step 3: Role Assignment
            $userCount = User::count();
            $adminExists = Role::where('name', 'admin')->exists();

            Log::info('Total Users: ' . $userCount);
            Log::info('Admin Role Exists? ' . $adminExists);

            $adminRole = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web',
            ]);

            if ($userCount === 1) {
                // No users in the table, assign the first user as admin
                Log::info('Assigning first user as admin.');
                $this->initializePermissionsForAdmin($adminRole);
                $user->assignRole($adminRole);
            } elseif (!$adminExists) {
                // Users exist but no admin role, create and assign
                Log::info('Assigning user as admin because no admin exists.');
                $this->initializePermissionsForAdmin($adminRole);
                $user->assignRole($adminRole);
            } else {
                // Assign investor role
                $employe = Employe::first();
                Investor::create([
                    'user_id' => $user->id,
                    'employe_id' => $employe?->id,
                    'reference' => 'direct',
                    'invest_date' => now()->toDateString(),
                ]);

                $investorRole = Role::firstOrCreate([
                    'name' => 'investor',
                    'guard_name' => 'web',
                ]);
                Log::info('Assigning user as investor.');
                $user->assignRole($investorRole);
            }




            insertKyc($user->id);

            Wallet::create([
                'user_id' => $user->id,
                'capital' => 0,
                'bonus' => 0,
                'profit' => 0,
                'is_locked' => true,
            ]);

            $data = [
                'user' => $user,
            ];



            // register the user to second website

            try {
                // Check if the connection to the second database is successful
                if (DB::connection('mysql2')->getPdo()) {
                    // Insert user into the second database and get the inserted ID
                    $userId = DB::connection('mysql2')->table('users')->insertGetId([
                        'email' => $user->email,
                        'password' => $user->password, // Already hashed
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    // Insert role-user mapping using the new user ID
                    DB::connection('mysql2')->table('role_user')->insert([
                        'role_id' => 1,
                        'user_id' => $userId, // Use the retrieved ID
                    ]);
                } else {
                    // If there's no connection, ignore the actions
                    Log::warning('No connection to the second database (mysql2). User not registered in role_user.');
                }
            } catch (\Exception $e) {
                // Log error if the connection fails
                Log::error('Error connecting to the second database (mysql2): ' . $e->getMessage());
            }
            // end

            sendEmail($user->email, 'registration', $data);



            // send notification
            sendNotification($user->id, 'info', 'Welcome Notification', 'We welcome you to our family');

            // send email to user


            User::role('admin')->chunk(100, function ($admins) use ($user) {
                foreach ($admins as $admin) {
                    sendNotification($admin->id, 'info', 'Investor Notification', 'The Investor : ' . $user->name . ' has created new account.');
                }
            });


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Creation Failed:', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => 'Something went wrong during registration.']);
        }




        event(new Registered($user));



                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
            'message' => 'User registered successfully. Please log in.',
        ], 201);




    }





    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::attempt($credentials)) {
        $user = Auth::user()
            ->load([
                'transactions',
                'wallet',
                'investor',
                'employe',
                'referrals',
                'referralLinks',
                'kycRequest',
                'contract',
                'latestTransaction',
                'sessions'
            ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    return response()->json([
        'message' => 'Invalid credentials',
        'errors' => ['email' => ['The provided credentials do not match our records.']],
    ], 401);
}


    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent to your email'], 200)
            : response()->json(['message' => 'Unable to send reset link'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'], 200)
            : response()->json(['message' => 'Unable to reset password', 'errors' => ['email' => [__($status)]]], 400);
    }
}
