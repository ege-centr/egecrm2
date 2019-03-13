<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Phone\PhoneResource;
use App\Http\Resources\Photo\PhotoResource;

class Resource extends JsonResource
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
        return array_merge(parent::toArray($request), [
            'name' => $name ?: $this->nickname,
            'phones' => PhoneResource::collection($this->phones),
            'photo' => new PhotoResource($this->photo),
            'email' => $this->email,
            'ips' => $this->ips
        ]);
    }
}
