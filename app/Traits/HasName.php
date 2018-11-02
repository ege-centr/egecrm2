<?php

namespace App\Traits;

trait HasName
{
    public function getNamesAttribute()
    {
        return (object)[
            'full' => implode(' ', [$this->last_name, $this->first_name, $this->middle_name]),
            'short' => implode(' ', [$this->first_name, $this->last_name]),
            'abbreviation' => $this->last_name . ' ' . mb_substr($this->first_name, 0, 1) . '. ' . mb_substr($this->middle_name, 0, 1) . '.'
        ];
    }

    public function scopeOrderByName($query)
    {
        return $query->orderByRaw('last_name asc, first_name asc, middle_name asc');
    }
}