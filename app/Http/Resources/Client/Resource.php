<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => $this->phones,
            'name' => getName($this),
            'requests' => $this->getRequests(),
            'markers' => $this->markers,
            'passport' => $this->passport,
            'email' => $this->email,
            'photo' => $this->photo
        ]);
    }
}
