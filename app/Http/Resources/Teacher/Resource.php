<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Phone\PhoneResource;
use App\Http\Resources\Photo\PhotoResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'names', 'email', 'subjects_ec', 'default_name'
        ], [
            'phones' => $this->phones,
            'photo' => $this->photo,
        ]);
    }
}
