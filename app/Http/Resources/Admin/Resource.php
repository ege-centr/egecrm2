<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

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
        return array_merge(parent::toArray($request), [
            'name' => implode(' ', [$this->last_name, $this->first_name]),
            'phones' => $this->phones,
            'photo' => $this->photo,
            'email' => $this->email,
            'ips' => $this->ips
        ]);
    }
}
