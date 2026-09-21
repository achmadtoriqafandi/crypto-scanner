<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthOrPublicAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If public access mode is enabled via PUBLIC_ACCESS=true in .env
        if (config('auth.public_access', false)) {
            return $next($request);
        }

        // Otherwise require standard Laravel authentication
        if (Auth::check()) {
            return $next($request);
        }

        return redirect()->guest(route('login'));
    }
}
