<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'names', 'photo', 'phones', 'email', 'subjects_ec'
        ]);
    }
}
