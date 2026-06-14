<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // HSTS - Enforce HTTPS (1 year)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // X-Frame-Options - Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options - Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-XSS-Protection - Legacy XSS protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy - Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy - Disable risky APIs
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=()');

        // Content-Security-Policy - XSS and injection protection
        $csp = "default-src 'self'; " .
               "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://unpkg.com; " .
               "style-src 'self' 'unsafe-inline' https://fonts.bunny.net; " .
               "font-src 'self' https://fonts.bunny.net; " .
               "img-src 'self' data: https: blob:; " .
               "connect-src 'self' https:; " .
               "frame-ancestors 'none'; " .
               "form-action 'self'; " .
               "base-uri 'self'; " .
               "object-src 'none'; " .
               "media-src 'self' data: https:";

        $response->headers->set('Content-Security-Policy', $csp);

        // Remove sensitive headers
        $response->headers->remove('Server');
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('X-AspNet-Version');
        $response->headers->remove('X-AspNetMvc-Version');

        // Disable browser caching for sensitive pages
        if ($request->route() && in_array($request->route()->getName(), ['login', 'register', 'dashboard', 'admin.*'])) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
        }

        return $response;
    }
}
