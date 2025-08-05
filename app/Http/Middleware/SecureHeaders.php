<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        //  Prevent MIME sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        //  Clickjacking protection
        $response->headers->set('X-Frame-Options', 'DENY');

        // Referrer control
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Browser feature restrictions
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=()');

        // Enforce HTTPS (ONLY IF you're using SSL)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');

        // Content Security Policy (CSP)
        $response->headers->set('Content-Security-Policy', "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline'; object-src 'none'; frame-ancestors 'none'");

        //  XSS Protection (not necessary anymore, but legacy)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        return $response;
    }
}
