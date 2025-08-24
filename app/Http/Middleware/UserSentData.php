<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserSentData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // التحقق من حالة KYC للمستخدم
        if ($user->kycRequest->status === 'processing') {
            // يمكنه المتابعة إذا كانت "processing"
            return $next($request);
        } elseif ($user->kycRequest->status === 'completed') {
            // يمكنه المتابعة إذا كانت "completed"
            return $next($request);
        }

        // إذا كانت أي حالة أخرى، يتم حظر الوصول
        return redirect()->route('investor.investor_analytics.index')->withErrors([
            'error' => 'تم رفض الوصول. حالتك الحالية في KYC: ' . $user->kycRequest->status,
        ]);
    }

}
