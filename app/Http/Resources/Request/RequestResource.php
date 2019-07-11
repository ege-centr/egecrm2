<?php

namespace App\Http\Resources\Request;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Phone\PhoneResource;

class RequestResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'get_back_date' => $this->get_back_at ? date('Y-m-d', strtotime($this->get_back_at)) : null,
            'get_back_time' => $this->get_back_at ? date('H:i', strtotime($this->get_back_at)) : null,
            'phones' => PhoneResource::collection($this->phones),
            'createdUser' => new \PersonResource($this->createdUser),
        ]);
    }
}
