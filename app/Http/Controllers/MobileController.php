<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Employe;
use App\Models\Investor;
use App\Models\ReferralLink;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class MobileController extends Controller
{
    public function register(Request $request)
    {

      
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed'],
        ]);

       
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

            
            

            // send notification
            sendNotification($user->id, 'info', 'Welcome Notification', 'We welcome you to our family');
            User::role('admin')->chunk(100, function ($admins) use ($user) {
                foreach ($admins as $admin) {
                    sendNotification($admin->id, 'info', 'Investor Notification', 'The Investor : ' . $user->name . ' has created new account.');
                }
            });


            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Creation Failed:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong during registration.',
                'error' => $e->getMessage(),
            ], 500);
        }

        event(new Registered($user));

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. You can now log in.',
            'user' => $user,
        ], 201);
    }


    public function login(LoginRequest $request)
    {
        $request->authenticate();

        $user = Auth::user();

        $token = $user->createToken('auth-token')->plainTextToken;

        $request->session()->regenerate();

        $request->session()->put('token', $token);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful. You can now log in.',
            'token' => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken();
    
        if ($token) {
            $token->delete();
        }
    
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
    

    /**
     * Get the authenticated User
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    //private functions 
    private function initializePermissionsForAdmin(Role $role): void
    {
        // Initialize permissions only if they haven't been set
        if ($role->permissions()->count() === 0) {
            // Create permissions based on defined actions and resources
            $this->permissions_init();

            // Retrieve all permissions and assign them to the role
            $permissions = Permission::all();

            // Assign permissions to the role
            $role->syncPermissions($permissions);
        }
    }

    private function permissions_init(): void
    {
        // Get the list of actions and resources
        $actions = permissions_list();
        $resources = routes_list();

        // Iterate over each resource and action to create permissions
        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                // Combine action and resource to form the permission name
                $permissionName = $action . " " . $resource;
                // $permissionName = str_replace('-', ' ', $actions);
                // Create the permission if it doesn't already exist
                Permission::firstOrCreate(['name' => $permissionName]);
            }
        }
    }

}
