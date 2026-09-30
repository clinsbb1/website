<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;

/**
 * Verifies a Cloudflare Turnstile response token server-side. If Turnstile
 * isn't configured (no secret key in .env — e.g. local dev or tests), the
 * check is skipped entirely rather than locking anyone out.
 */
class ValidTurnstileToken implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $secret = config('services.turnstile.secret');

        if (! $secret) {
            return;
        }

        if (! $value) {
            $fail('Please complete the verification challenge.');

            return;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret' => $secret,
            'response' => $value,
            'remoteip' => request()->ip(),
        ]);

        if (! $response->successful() || $response->json('success') !== true) {
            $fail('Verification failed. Please try again.');
        }
    }
}
