<?php

use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\GenerateMonthlyStatementsJob;
use App\Jobs\CheckUserRulesJob;
use App\Models\User;

// Define the rules:dispatch command
Artisan::command('rules:dispatch', function () {
    $chunkSize = 1000;

    User::select('id')->chunk($chunkSize, function ($users) {
        $userIds = $users->pluck('id')->toArray();
        CheckUserRulesJob::dispatch($userIds);

        \Log::info('Dispatched job for user IDs: ' . implode(',', $userIds));
    });

    $this->info('Jobs dispatched successfully.');
})->purpose('Dispatch jobs to check user rules in chunks');

// Schedule tasks
Schedule::call(function () {
    Artisan::call('rules:dispatch');
    \Log::info('Scheduled rules:dispatch command executed.');
})->everyMinute();

Schedule::job(new GenerateMonthlyStatementsJob)
    ->monthlyOn(31, '23:59') // Runs on the last day of the month at 11:59 PM
    ->timezone('UTC')
    ->when(function () {
        // Ensures it only runs on the actual last day of the month
        return now()->endOfMonth()->isToday();
    });