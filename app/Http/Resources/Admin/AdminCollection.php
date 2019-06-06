<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

class AdminCollection extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = trim(implode(' ', [$this->first_name, $this->last_name]));
        return [
            'id' => $this->id,
            'default_name' => $name ?: $this->nickname,
            'is_banned' => $this->isBanned(),
            'email_id' => optional($this->email)->id,
            'photo' => new PhotoResource($this->photo),
        ];
    }
}
