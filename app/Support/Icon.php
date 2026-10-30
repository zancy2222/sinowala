<?php

namespace App\Support;

/** Lucide icons (ISC licence), copied into resources/icons. One set, one stroke, everywhere. */
class Icon
{
    private static array $cache = [];

    public static function inner(string $name): string
    {
        if (! preg_match('/^[a-z0-9-]+$/', $name)) {
            return '';
        }

        return self::$cache[$name] ??= (function () use ($name) {
            $path = resource_path('icons/'.$name.'.svg');
            if (! is_file($path)) {
                return '';
            }
            $svg = file_get_contents($path);

            return preg_match('#<svg[^>]*>(.*)</svg>#s', $svg, $m) ? trim($m[1]) : '';
        })();
    }
}
