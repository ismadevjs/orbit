<?php

namespace App\Observers;

use App\Models\Wallet;
use Illuminate\Support\Facades\Log;

class WalletObserver
{
    /**
     * Handle the Wallet "updated" event.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return void
     */
    public function updated(Wallet $wallet)
    {
        Log::info("WalletObserver: Wallet ID {$wallet->id} has been updated.");

        if ($wallet->user && $wallet->user->investor) {
            Log::info("WalletObserver: Wallet ID {$wallet->id} belongs to User ID {$wallet->user->id} with Investor ID {$wallet->user->investor->id}.");
            checkInvestorRules($wallet->user->investor);
            checkAdvertiserRules($wallet->user->investor);
            checkManagerRules($wallet->user->investor);
        } else {
            Log::warning("WalletObserver: Wallet ID {$wallet->id} does not have an associated Investor through User ID " . ($wallet->user->id ?? 'N/A') . ".");
        }
    }

    /**
     * Handle the Wallet "created" event.
     *
     * @param \App\Models\Wallet $wallet
     */
    public function created(Wallet $wallet)
    {
        Log::info("WalletObserver: Wallet ID {$wallet->id} has been created.");

        if ($wallet->user && $wallet->user->investor) {
            Log::info("WalletObserver: Wallet ID {$wallet->id} belongs to User ID {$wallet->user->id} with Investor ID {$wallet->user->investor->id}.");
            checkInvestorRules($wallet->user->investor);
            checkAdvertiserRules($wallet->user->investor);
            checkManagerRules($wallet->user->investor);
        } else {
            Log::warning("WalletObserver: Wallet ID {$wallet->id} does not have an associated Investor through User ID " . ($wallet->user->id ?? 'N/A') . ".");
        }
    }
}
