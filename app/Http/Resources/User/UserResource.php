<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'type' => get_class($this->resource),
            'photo' => new PhotoResource($this->photo),
            'email' => $this->email_string,
            'name' => $this->default_name,
        ];
    }
}
