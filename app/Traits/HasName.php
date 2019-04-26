<?php

namespace App\Traits;

trait HasName
{
    public function getNamesAttribute()
    {
        return (object)[
            'full' => implode(' ', [$this->last_name, $this->first_name, $this->middle_name]),
            'short' => implode(' ', [$this->first_name, $this->last_name]),
            'lastF' => $this->last_name . ' ' . mb_substr($this->first_name, 0, 1) . '.',
            'abbreviation' => $this->last_name . ' ' . mb_substr($this->first_name, 0, 1) . '. ' . mb_substr($this->middle_name, 0, 1) . '.'
        ];
    }

    public function getDefaultNameAttribute()
    {
        return $this->names->short;
    }

    public function scopeSearchByName($query, $text)
    {
        return $query->where(function ($query) use ($text) {
            $query->where('first_name', 'like', "%{$text}%")
                ->orWhere('last_name', 'like', "%{$text}%")
                ->orWhere('middle_name', 'like', "%{$text}%");
        });
    }

    public function scopeOrderByName($query)
    {
        return $query->orderByRaw('last_name asc, first_name asc, middle_name asc');
    }
}
