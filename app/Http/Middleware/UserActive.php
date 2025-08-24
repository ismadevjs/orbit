<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            if ($request->route()->getName() !== 'login') {
                return redirect()->route('login');
            }
            return $next($request);
        }

        // Prioritize employees
   
        if (Auth::user()->hasRole('employee')) {
            return $next($request);
        }

        // Redirect non-active users (except on the KYC page)
        if (!Auth::user()->active && $request->route()->getName() !== 'investor.kyc-verification') {
            return redirect()->route('investor.kyc-verification');
        }

        return $next($request);
    }
}
