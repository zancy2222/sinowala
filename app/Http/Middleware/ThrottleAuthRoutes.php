<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

/** Rate limits for Fortify POST routes that have no limiter of their own. */
class ThrottleAuthRoutes
{
    private const LIMITERS = [
        'register.store' => 'register',
        'password.email' => 'password-reset',
        'password.update' => 'password-reset',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('post')) {
            return $next($request);
        }

        foreach (self::LIMITERS as $route => $name) {
            if (! $request->routeIs($route)) {
                continue;
            }
            $limits = RateLimiter::limiter($name)($request);
            foreach (is_array($limits) ? $limits : [$limits] as $limit) {
                $key = $name.':'.$limit->key;
                if (RateLimiter::tooManyAttempts($key, $limit->maxAttempts)) {
                    abort(429);
                }
                RateLimiter::hit($key, $limit->decaySeconds);
            }
        }

        return $next($request);
    }
}
