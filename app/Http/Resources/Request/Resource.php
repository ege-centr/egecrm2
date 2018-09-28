<?php

namespace App\Http\Resources\Request;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => $this->phones,
        ]);
    }
}
