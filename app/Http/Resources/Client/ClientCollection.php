<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Photo\PhotoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'names' => $this->names,
            'photo' => new PhotoResource($this->photo),
            'default_name' => $this->default_name,
        ];
    }
}
