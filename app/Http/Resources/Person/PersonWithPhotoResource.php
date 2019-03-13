<?php

namespace App\Http\Resources\Person;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class PersonWithPhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'names' => $this->names,
            'default_name' => $this->default_name,
            'photo' => new PhotoResource($this->photo),
        ]);
    }
}
