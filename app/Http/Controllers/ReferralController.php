<?php

namespace App\Http\Controllers;

use App\Models\ReferralLink;
use App\Models\Responsible;
use App\Models\Employe;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ReferralController extends Controller
{

    public function generate ()
    {
        $links = Auth::user()->referralLinks()->get();
        return view('backend.affiliates.generate', compact('links'));
    }
    public function generateLink()
    {
        $user = Auth::user();

        // Determine expiration time based on user role
        if ($user->hasRole('advertiser')) {
            $expiresAt = now()->addDay()->toDateTimeString(); // 1 day for advertiser
        } elseif ($user->hasRole('investor_manager') || $user->hasRole('manager')) {
            $expiresAt = now()->addYear()->toDateTimeString(); // 1 year for investor_manager and manager
        } else {
            $expiresAt = now()->addDays(1000)->toDateTimeString(); // Default: 1000 days
        }

        // Prepare referral data
        $data = [
            'referrer_id' => $user->id,
            'expires_at' => $expiresAt,
        ];
        // Encrypt and encode the token securely
        $encryptedToken = base64_encode(Crypt::encryptString(json_encode($data)));

        // Save or update the referral link
        ReferralLink::updateOrCreate(
            ['user_id' => $user->id],
            ['encrypted_token' => $encryptedToken, 'expires_at' => $data['expires_at']]
        );

        // Generate the referral link
        $link = url('/register?ref=' . $encryptedToken);

        return response()->json([
            'link' => $link,
            'expires_at' => $data['expires_at']
        ]);
    }




    public function myReferrals()
    {
        $user = Auth::user();
        $referrals = $user->referrals()->get();
        Log::info("User ID: {$user->id}, Referral count: {$referrals->count()}");
    
        if ($user->hasRole('employee') && $referrals->isNotEmpty()) {
            Log::info("User has 'employee' role, processing referrals.");
    
            // Fetch the employee record associated with the user
            $employee = Employe::where('id', $user->employe->id ?? null)->first();
    
            if (!$employee) {
                Log::warning("User ID {$user->id} is not a valid employee.");
                return view('backend.affiliates.referrals', compact('referrals'));
            }
            Log::info("Employee validated for user ID: {$user->id}");
    
            foreach ($referrals as $referral) {
                Log::info("Processing referral ID: {$referral->id}, Investor ID: {$referral->id}");
    
                // Check if the referral is already assigned to another employee
                $existingAssignment = Responsible::where('investor_id', $referral->id)
                    ->where('employe_id', '!=', $employee->id)
                    ->first();
    
                if ($existingAssignment) {
                    Log::info("Referral ID {$referral->id} already assigned to employee ID: {$existingAssignment->employe_id}");
                    continue;
                }
    
                // Check if the Responsible record already exists
                $exists = Responsible::where('employe_id', $employee->id)
                    ->where('investor_id', $referral->id)
                    ->exists();
                Log::info("Responsible exists check: employe_id={$employee->id}, investor_id={$referral->id}, exists=" . ($exists ? 'true' : 'false'));
    
                if (!$exists) {
                    Log::info("Attempting to create Responsible for employe_id={$employee->id}, investor_id={$referral->id}");
                    try {
                        Responsible::create([
                            'employe_id' => $employee->id, // Use employee ID, not user ID
                            'investor_id' => $referral->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        Log::info("Successfully created Responsible for employe_id={$employee->id}, investor_id={$referral->id}");
                    } catch (\Exception $e) {
                        Log::error("Failed to create Responsible: " . $e->getMessage());
                    }
                }
            }
        }
    
        return view('backend.affiliates.referrals', compact('referrals'));
    }
}
