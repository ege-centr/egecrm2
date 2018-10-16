<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => getName($this, true),
            'name_short' => shortName($this)
        ];
    }
}
