<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Verified;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        // Behind Cloudflare / a load balancer: TRUSTED_PROXIES=* or a comma-separated list.
        if ($proxies = config('sinowala.trusted_proxies')) {
            TrustProxies::at($proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }

        // Min 8, mixed case + number, checked against known breached passwords.
        Password::defaults(fn () => Password::min(8)->mixedCase()->numbers()->uncompromised());

        Paginator::defaultView('components.ui.pagination');

        $this->rateLimiters();

        Event::listen(Login::class, function (Login $event) {
            if ($event->user instanceof User) {
                $event->user->forceFill(['last_login_at' => now()])->save();
                $event->user->syncSuperAdminFlag();
            }
        });

        Event::listen(Verified::class, function (Verified $event) {
            if ($event->user instanceof User) {
                $event->user->syncSuperAdminFlag();
            }
        });
    }

    private function rateLimiters(): void
    {
        $by = fn (Request $request, string $extra = '') => Str::lower($extra).'|'.$request->ip();

        RateLimiter::for('register', fn (Request $r) => Limit::perHour(10)->by($by($r)));
        RateLimiter::for('password-reset', fn (Request $r) => [
            Limit::perMinute(3)->by($by($r, (string) $r->input('email'))),
            Limit::perHour(20)->by($r->ip()),
        ]);
        RateLimiter::for('join-code', fn (Request $r) => Limit::perMinute(5)->by(($r->user()?->id ?? 'guest').'|'.$r->ip()));
        RateLimiter::for('contact', fn (Request $r) => Limit::perHour(5)->by($r->ip()));
        RateLimiter::for('privacy-request', fn (Request $r) => Limit::perHour(5)->by($r->ip()));
        RateLimiter::for('share-page', fn (Request $r) => Limit::perMinute(30)->by($r->ip()));
        RateLimiter::for('consent', fn (Request $r) => Limit::perMinute(20)->by($r->ip()));
        RateLimiter::for('export', fn (Request $r) => Limit::perHour(10)->by((string) $r->user()?->id));
        RateLimiter::for('app-actions', fn (Request $r) => Limit::perMinute(60)->by((string) $r->user()?->id));
    }
}
