<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
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
            'is_banned' => $this->isBanned(),
        ];
    }
}
