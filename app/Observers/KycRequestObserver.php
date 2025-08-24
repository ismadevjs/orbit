<?php

namespace App\Observers;

use App\Models\KYCRequest;
use Illuminate\Support\Facades\Log;

class KycRequestObserver
{
    /**
     * Handle the KycRequest "updated" event.
     *
     * @param  \App\Models\KYCRequest  $kycRequest
     * @return void
     */
    public function updated(KYCRequest $kycRequest)
    {
        Log::info("KycRequestObserver: KYCRequest ID {$kycRequest->id} has been updated.");

        if ($kycRequest->user && $kycRequest->user->investor) {
            Log::info("KycRequestObserver: KYCRequest ID {$kycRequest->id} belongs to User ID {$kycRequest->user->id} with Investor ID {$kycRequest->user->investor->id}.");
            checkInvestorRules($kycRequest->user->investor);
            checkAdvertiserRules($kycRequest->user->investor);
            checkManagerRules($kycRequest->user->investor);
        } else {
            Log::warning("KycRequestObserver: KYCRequest ID {$kycRequest->id} does not have an associated Investor through User ID " . ($kycRequest->user->id ?? 'N/A') . ".");
        }
    }

    /**
     * Handle the KycRequest "created" event.
     *
     * @param \App\Models\KYCRequest $kycRequest
     */
    public function created(KYCRequest $kycRequest)
    {
        Log::info("KycRequestObserver: KYCRequest ID {$kycRequest->id} has been created.");

        if ($kycRequest->user && $kycRequest->user->investor) {
            Log::info("KycRequestObserver: KYCRequest ID {$kycRequest->id} belongs to User ID {$kycRequest->user->id} with Investor ID {$kycRequest->user->investor->id}.");
            checkInvestorRules($kycRequest->user->investor);
            checkAdvertiserRules($kycRequest->user->investor);
            checkManagerRules($kycRequest->user->investor);
        } else {
            Log::warning("KycRequestObserver: KYCRequest ID {$kycRequest->id} does not have an associated Investor through User ID " . ($kycRequest->user->id ?? 'N/A') . ".");
        }
    }
}
