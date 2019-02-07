<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'names' => $this->names,
            'photo_url' => $this->photo_url,
            'subjects_ec' => $this->subjects_ec,
        ];
    }
}
