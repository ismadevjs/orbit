<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        Log::info("UserObserver: User ID {$user->id} has been updated.");
        $user->refresh();
        if ($user->investor) {
            Log::info("UserObserver: User ID {$user->id} has Investor ID {$user->investor->id}.");
            checkInvestorRules($user->investor);
            checkAdvertiserRules($user->investor);
            checkManagerRules($user->investor);
        } else {
            Log::warning("UserObserver: User ID {$user->id} does not have an associated Investor.");
        }
    }

    /**
     * Handle the User "created" event.
     *
     * @param \App\Models\User $user
     */
    public function created(User $user)
    {
        Log::info("UserObserver: User ID {$user->id} has been created.");

        if ($user->investor) {
            Log::info("UserObserver: User ID {$user->id} has Investor ID {$user->investor->id}.");
            checkInvestorRules($user->investor);
            checkAdvertiserRules($user->investor);
            checkManagerRules($user->investor);
        } else {
            Log::warning("UserObserver: User ID {$user->id} does not have an associated Investor.");
        }
    }
}
