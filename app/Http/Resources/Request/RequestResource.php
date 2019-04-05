<?php

namespace App\Http\Resources\Request;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Phone\PhoneResource;

class RequestResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => PhoneResource::collection($this->phones),
            'createdUser' => new \PersonResource($this->createdUser),
        ]);
    }
}
