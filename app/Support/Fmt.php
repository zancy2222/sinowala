<?php

namespace App\Support;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;

/** Taglish date formatting, e.g. "Martes, Okt 27, 2026". Names live in lang/fil/app.php. */
class Fmt
{
    public static function parse(CarbonInterface|string $date): CarbonInterface
    {
        return $date instanceof CarbonInterface ? $date : Carbon::parse($date, config('app.timezone'));
    }

    public static function day(CarbonInterface|string $date): string
    {
        return __('app.days.'.self::parse($date)->dayOfWeek);
    }

    public static function dayShort(CarbonInterface|string $date): string
    {
        return __('app.days_short.'.self::parse($date)->dayOfWeek);
    }

    public static function month(CarbonInterface|string $date): string
    {
        return __('app.months.'.self::parse($date)->month);
    }

    public static function monthShort(CarbonInterface|string $date): string
    {
        return __('app.months_short.'.self::parse($date)->month);
    }

    /** Martes, Okt 27, 2026 */
    public static function date(CarbonInterface|string $date): string
    {
        $d = self::parse($date);

        return self::day($d).', '.self::monthShort($d).' '.$d->day.', '.$d->year;
    }

    /** Martes, Oktubre 27 */
    public static function long(CarbonInterface|string $date): string
    {
        $d = self::parse($date);

        return self::day($d).', '.self::month($d).' '.$d->day;
    }

    /** Okt 27 */
    public static function short(CarbonInterface|string $date): string
    {
        $d = self::parse($date);

        return self::monthShort($d).' '.$d->day;
    }

    /** Oktubre 2026 */
    public static function monthYear(CarbonInterface|string $date): string
    {
        $d = self::parse($date);

        return self::month($d).' '.$d->year;
    }

    /** Okt 26 – Nob 1, 2026 */
    public static function range(CarbonInterface $from, CarbonInterface $to): string
    {
        if ($from->isSameDay($to)) {
            return self::date($from);
        }

        $left = self::short($from).($from->year !== $to->year ? ', '.$from->year : '');

        return $left.' – '.self::short($to).', '.$to->year;
    }

    public static function dateTime(?CarbonInterface $at): string
    {
        if (! $at) {
            return '—';
        }
        $at = $at->copy()->setTimezone(config('app.timezone'));

        return self::short($at).', '.$at->year.' · '.$at->format('g:i A');
    }
}
