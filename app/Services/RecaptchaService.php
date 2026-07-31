<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RecaptchaService
{
    public function verify(string $token, ?string $ip = null): bool
    {
        $response = Http::asForm()->post(
            'https://www.google.com/recaptcha/api/siteverify',
            [
                'secret'   => config('services.frontend_recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ]
        );

        if ($response->failed()) {
            return false;
        }

        $result = $response->json();

        return $result['success'] ?? false;
    }

    public function response(string $token, ?string $ip = null): array
    {
        return Http::asForm()
            ->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret'   => config('services.frontend_recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => $ip,
            ])
            ->json();
    }
}
