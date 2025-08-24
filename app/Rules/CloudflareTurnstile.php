<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Http;

class CloudflareTurnstile implements Rule
{
    public function passes($attribute, $value)
    {
        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => config('services.turnstile.secret_key'),
            'response' => $value,
            'remoteip' => request()->ip(),
        ])->json();

        return $response['success'] ?? false;
    }

    public function message()
    {
        return 'Turnstile verification failed. Please try again.';
    }
}
