<?php

namespace App\Traits;
use Cache;

trait Cacheable
{
    public static function getCached()
    {
        return Cache::rememberForever(static::class, function () {
            return static::all();
        });
    }
}
