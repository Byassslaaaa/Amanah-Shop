<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    | ⚠️ SECURITY: Wildcard '*' allows ANY website to make requests to your API!
    | For production, restrict to your domain only.
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'payment/*'],

    // Only allow standard HTTP methods
    'allowed_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],

    // ⚠️ IMPORTANT: In production, replace with your actual domain(s)
    // Development: Allow localhost
    // Production: Only your domain
    'allowed_origins' => [
        env('APP_URL', 'http://localhost'),
        env('FRONTEND_URL', env('APP_URL')), // If you have separate frontend
        // Add production domains here:
        // 'https://amanahshop.com',
        // 'https://www.amanahshop.com',
    ],

    'allowed_origins_patterns' => [
        // Allow all localhost ports for development
        '/^http:\/\/localhost:\d+$/',
        '/^http:\/\/127\.0\.0\.1:\d+$/',
    ],

    // Only allow necessary headers
    'allowed_headers' => [
        'Content-Type',
        'Authorization',
        'X-Requested-With',
        'X-CSRF-Token',
        'Accept',
        'Origin',
    ],

    // Expose these headers to client-side JavaScript
    'exposed_headers' => [
        'Content-Range',
        'X-Content-Range',
    ],

    // Cache preflight requests for 1 hour (3600 seconds)
    'max_age' => 3600,

    // Allow cookies and authentication headers
    'supports_credentials' => true,

];
