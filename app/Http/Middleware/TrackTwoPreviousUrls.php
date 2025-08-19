<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class TrackTwoPreviousUrls
{
    public function handle($request, Closure $next)
    {
        // Get the current URL
        $currentUrl = url()->current();

        // Shift the current URL to second previous URL
        if (Session::has('previous_url')) {
            Session::put('second_previous_url', Session::get('previous_url'));
        }

        // Update the previous URL
        Session::put('previous_url', $currentUrl);

        return $next($request);
    }
}
