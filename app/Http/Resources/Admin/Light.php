<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class Light extends JsonResource
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
            'name' => $name ?: $this->nickname,
            'photo' => new PhotoResource($this->photo)
        ];
    }
}
