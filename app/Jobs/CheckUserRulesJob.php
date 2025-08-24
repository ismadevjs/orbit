<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class CheckUserRulesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userIds;

    /**
     * Create a new job instance.
     *
     * @param array $userIds
     */
    public function __construct(array $userIds)
    {
        $this->userIds = $userIds;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = User::whereIn('id', $this->userIds)->get();

        foreach ($users as $user) {
            // Ensure we are fetching the related Investor model
            $investor = $user->investor;

            if ($investor) {
                checkInvestorRules($investor); // Pass the correct Investor model
                checkAdvertiserRules($investor);
                checkManagerRules($investor);
                checkAdvertiserComissionRules($investor);
                checkManagerComissionRules($investor);
                incentives($investor);
                calculateAffiliateProfits($investor);
            } else {
                \Log::warning("User ID {$user->id} does not have an associated investor.");
            }


        }


    }


}
