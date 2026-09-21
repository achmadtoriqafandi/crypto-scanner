<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request and append security HTTP headers.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $headers = [
            'X-Frame-Options'           => 'SAMEORIGIN',
            'X-Content-Type-Options'    => 'nosniff',
            'X-XSS-Protection'          => '1; mode=block',
            'Referrer-Policy'           => 'strict-origin-when-cross-origin',
            'Permissions-Policy'        => 'camera=(), microphone=(), geolocation=()',
            'Content-Security-Policy'   => "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com data:; img-src 'self' data: https:;",
        ];

        if ($request->isSecure() || env('FORCE_HTTPS', false)) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains; preload';
        }

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
