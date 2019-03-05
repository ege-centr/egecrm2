<?php

namespace App\Http\Resources\Person;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonWithPhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'names' => $this->names,
            'photo' => $this->photo,
        ]);
    }
}
