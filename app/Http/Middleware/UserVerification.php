<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Check if the user's KYC status is not "pending"
        if ($user->kycRequest->status !== 'pending') {
            // Prevent loop redirect
            if ($request->route()->getName() === 'investor.investor_analytics.index') {
                return $next($request);
            }

            // Redirect the user with a message
            return redirect()->route('investor.investor_analytics.index')->withErrors([
                'error' => 'لا يمكنك الوصول إلى هذه الصفحة. حالتك الحالية: ' . $user->kycRequest->status,
            ]);
        }

        return $next($request);
    }
}
