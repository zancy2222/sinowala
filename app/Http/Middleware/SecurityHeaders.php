<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        Vite::useCspNonce();

        $response = $next($request);

        $nonce = Vite::cspNonce();
        $scriptSrc = ["'self'", "'nonce-{$nonce}'", "'unsafe-eval'"]; // unsafe-eval: Alpine (bundled with Livewire 3) evaluates x-* expressions.
        $connectSrc = ["'self'"];

        if ($analytics = config('sinowala.analytics.id') ? config('sinowala.analytics.src') : null) {
            $origin = parse_url($analytics, PHP_URL_SCHEME).'://'.parse_url($analytics, PHP_URL_HOST);
            $scriptSrc[] = $origin;
            $connectSrc[] = $origin;
        }

        // Local `npm run dev` only: allow the Vite dev server.
        $hot = '';
        if (Vite::isRunningHot()) {
            $origin = rtrim(file_get_contents(public_path('hot')));
            $scriptSrc[] = $origin;
            $connectSrc[] = $origin;
            $connectSrc[] = preg_replace('#^http#', 'ws', $origin);
            $hot = ' '.$origin;
        }

        $csp = implode('; ', array_filter([
            "default-src 'self'",
            'script-src '.implode(' ', $scriptSrc),
            "style-src 'self' 'unsafe-inline'".$hot,
            "img-src 'self' data:",
            "font-src 'self'".$hot,
            'connect-src '.implode(' ', $connectSrc),
            "frame-ancestors 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "object-src 'none'",
            $request->isSecure() ? 'upgrade-insecure-requests' : null,
        ]));

        $headers = [
            'Content-Security-Policy' => $csp,
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'Permissions-Policy' => 'camera=(), microphone=(), geolocation=(), payment=(), usb=()',
            'X-Frame-Options' => 'DENY',
            'Cross-Origin-Opener-Policy' => 'same-origin',
        ];

        if ($request->isSecure() && app()->isProduction()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }
        $response->headers->remove('X-Powered-By');

        return $response;
    }
}
