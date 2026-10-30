<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class Today
{
    public static function date(): CarbonImmutable
    {
        return CarbonImmutable::now(config('app.timezone'))->startOfDay();
    }
}
