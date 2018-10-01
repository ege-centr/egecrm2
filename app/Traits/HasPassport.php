<?php

namespace App\Traits;
use App\Models\Passport;

trait HasPassport
{
    public function passport()
    {
        return $this->morphOne(Passport::class, 'entity');
    }
}
