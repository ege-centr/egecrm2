<?php

namespace App\Traits;

trait HasName
{
    public function scopeOrderByName($query)
    {
        return $query->orderByRaw('last_name asc, first_name asc, middle_name asc');
    }
}
