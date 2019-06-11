<?php

namespace App\Models\Factory;

// WARNING: Это на самом деле не Factory

class Year
{
    const DISABLE_LOGS = true;
    const ALL = [2015, 2016, 2017, 2018, 2019];

    public static function all()
    {
        return array_map(function($year) {
            return [
                'id' => $year,
                'title' => $year . '–' . ($year + 1) . ' уч. г.',
            ];
        }, self::ALL);
    }
}
