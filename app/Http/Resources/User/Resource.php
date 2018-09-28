<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'login' => $this->login,
            'name' => implode(' ', [$this->first_name, $this->last_name]),
            'photo_url' => $this->photo_url
        ];
    }
}
