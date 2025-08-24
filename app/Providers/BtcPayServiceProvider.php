<?php

namespace App\Providers;

use App\Services\BtcPayService;
use Illuminate\Support\ServiceProvider;

class BtcPayServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BtcPayService::class, function ($app) {
            return new BtcPayService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
