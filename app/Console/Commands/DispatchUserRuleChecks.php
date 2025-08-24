<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CheckUserRulesJob;
use App\Models\User;

class DispatchUserRuleChecks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rules:dispatch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dispatch jobs to check user rules in chunks';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $chunkSize = 1000; // Number of users per chunk

        User::select('id')->chunk($chunkSize, function ($users) {
            $userIds = $users->pluck('id')->toArray();
            CheckUserRulesJob::dispatch($userIds);
        });

        $this->info('Jobs dispatched successfully.');
    }
}
