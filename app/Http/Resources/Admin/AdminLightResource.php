<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class AdminLightResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = trim(implode(' ', [$this->last_name, $this->first_name]));
        return [
            'id' => $this->id,
            'default_name' => $name ?: $this->nickname,
            'photo' => new PhotoResource($this->photo)
        ];
    }
}
