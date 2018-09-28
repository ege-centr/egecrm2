<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => $this->phones,
            'full_name' => fullName($this),
            'requests' => $this->getRequests(),
        ]);
    }
}
