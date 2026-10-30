<?php

namespace App\Support;

use Illuminate\Http\Request;

/** Reads the visitor's cookie choice (set by the banner script). */
class CookieConsent
{
    public const COOKIE = 'sw_consent';

    public static function choices(?Request $request = null): ?array
    {
        $request ??= request();
        $raw = $request->cookie(self::COOKIE);
        if (! is_string($raw) || strlen($raw) > 500) {
            return null;
        }

        $data = json_decode(rawurldecode($raw), true);
        if (! is_array($data) || ($data['v'] ?? null) !== config('sinowala.cookie_version')) {
            return null;
        }

        return ['analytics' => (bool) ($data['analytics'] ?? false)];
    }

    public static function analyticsAllowed(): bool
    {
        return filled(config('sinowala.analytics.id')) && (self::choices()['analytics'] ?? false);
    }
}
