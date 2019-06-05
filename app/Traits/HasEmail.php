<?php

namespace App\Traits;
use App\Models\Email;

trait HasEmail
{
    public function email()
    {
        return $this->morphOne(Email::class, 'entity');
    }

    public function getEmailStringAttribute()
    {
        return optional($this->email)->email;
    }
}
