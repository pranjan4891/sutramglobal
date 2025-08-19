<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifyRecaptcha
{
    public function handle(Request $request, Closure $next)
    {
        $recaptchaToken = $request->input('g-recaptcha-response');
        $recaptchaSecret = env('RECAPTCHA_SECRET_KEY');

        if (!$recaptchaToken) {
            return back()->withErrors(['captcha' => 'reCAPTCHA token is missing.']);
        }

        $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $recaptchaSecret,
            'response' => $recaptchaToken,
        ]);

        $responseData = $response->json();

        if (!$responseData['success'] || ($responseData['score'] ?? 0) < 0.5) {
            return back()->withErrors(['captcha' => 'reCAPTCHA verification failed.']);
        }

        return $next($request);
    }
}

