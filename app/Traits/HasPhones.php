<?php

namespace App\Traits;
use App\Models\Phone;

trait HasPhones
{
    public function phones()
    {
        return $this->morphMany(Phone::class, 'entity');
    }

    public function getPhonesArray()
    {
        return $this->phones()->pluck('phone')->all();
    }
}
