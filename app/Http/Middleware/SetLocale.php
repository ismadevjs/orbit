<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        // Log current request
        Log::info('SetLocale Middleware: Current URL: ' . $request->url());
        Log::info('SetLocale Middleware: Session locale before: ' . Session::get('locale', 'none'));
        Log::info('SetLocale Middleware: App locale before: ' . App::getLocale());

        if (Session::has('locale')) {
            $locale = Session::get('locale', 'en');
            App::setLocale($locale);
            Log::info('SetLocale Middleware: App locale set to: ' . $locale);
        }

        return $next($request);
    }
}
