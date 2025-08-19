<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class TrackPreviousUrls
{
    public function handle($request, Closure $next)
    {
        // Store the current URL as the second previous URL
        if (Session::has('current_url')) {
            Session::put('second_previous_url', Session::get('current_url'));
        }

        // Update the current URL
        Session::put('current_url', url()->current());

        return $next($request);
    }
}
