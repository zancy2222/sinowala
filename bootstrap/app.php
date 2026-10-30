<?php

use App\Http\Middleware\EnsureAccountActive;
use App\Http\Middleware\EnsurePoliciesAccepted;
use App\Http\Middleware\EnsureSuperAdmin;
use App\Http\Middleware\ForceHttps;
use App\Http\Middleware\NoIndex;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ThrottleAuthRoutes;
use App\Support\CookieConsent;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Trusted proxies come from config('sinowala.trusted_proxies'), set in AppServiceProvider.
        $middleware->prepend(ForceHttps::class);
        $middleware->append(SecurityHeaders::class);

        // The consent cookie is written by the banner script, so it is not encrypted.
        $middleware->encryptCookies(except: [CookieConsent::COOKIE]);

        $middleware->web(append: [
            ThrottleAuthRoutes::class,
            EnsureAccountActive::class,
        ]);

        $middleware->alias([
            'super-admin' => EnsureSuperAdmin::class,
            'policies' => EnsurePoliciesAccepted::class,
            'noindex' => NoIndex::class,
        ]);

        $middleware->redirectGuestsTo(fn () => route('login'));
        $middleware->redirectUsersTo(fn () => route('app.home'));
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Never write secrets or private notes to the logs.
        $exceptions->dontFlash(['password', 'password_confirmation', 'current_password', 'code', 'recovery_code', 'note']);

        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
