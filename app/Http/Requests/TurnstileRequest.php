<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TurnstileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cf-turnstile-response' => ['required', function ($attribute, $value, $fail) {
                $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret'   => config('services.turnstile.secret_key'),
                    'response' => $value,
                    'remoteip' => $this->ip(),
                ])->json();

                if (!($response['success'] ?? false)) {
                    $fail('Turnstile verification failed.');
                }
            }],
        ];
    }
}
