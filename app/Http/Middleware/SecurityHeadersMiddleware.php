<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeadersMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Add security headers to protect against common attacks:
     * - X-Frame-Options: Prevent clickjacking
     * - X-Content-Type-Options: Prevent MIME-sniffing
     * - X-XSS-Protection: Enable browser XSS protection
     * - Referrer-Policy: Control referrer information
     * - Strict-Transport-Security: Enforce HTTPS (production only)
     * - Content-Security-Policy: Prevent XSS and injection attacks
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking - site cannot be embedded in iframe
        $response->headers->set('X-Frame-Options', 'DENY');

        // Prevent MIME-sniffing attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Enable browser XSS protection (legacy browsers)
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Control referrer information sent with requests
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Enforce HTTPS in production (HSTS)
        if (app()->environment('production')) {
            // max-age=31536000 = 1 year
            // includeSubDomains = apply to all subdomains
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains'
            );
        }

        // Content Security Policy - Strict but allows necessary resources
        // Note: Adjust based on your needs (e.g., add CDN domains)
        $csp = implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://unpkg.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://app.midtrans.com https://app.sandbox.midtrans.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdnjs.cloudflare.com",
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com",
            "img-src 'self' data: https: http:",
            "connect-src 'self' https://api.biteship.com https://api.midtrans.com https://api.sandbox.midtrans.com https://nominatim.openstreetmap.org",
            "frame-src 'self' https://app.midtrans.com https://app.sandbox.midtrans.com",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
        ]);

        $response->headers->set('Content-Security-Policy', $csp);

        // Permissions Policy (formerly Feature-Policy)
        // Disable potentially dangerous browser features
        $response->headers->set(
            'Permissions-Policy',
            'geolocation=(), microphone=(), camera=(), payment=(self)'
        );

        return $response;
    }
}
