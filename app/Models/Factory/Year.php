<?php

namespace App\Models\Factory;

// WARNING: Это на самом деле не Factory

class Year
{
    const DISABLE_LOGS = true;
    const ALL = [2015, 2016, 2017, 2018];

    public static function all()
    {
        // TODO: убрать value, и вообще привести всё к единому виду
        return array_map(function($year) {
            return ['id' => $year, 'value' => $year, 'text' => $year . '–' . ($year + 1) . ' уч. г.'];
        }, self::ALL);
    }
}
