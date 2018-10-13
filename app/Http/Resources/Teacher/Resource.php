<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Contract\Resource as ContractResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => $this->phones,
            'name' => getName($this),
            'requests' => $this->getRequests(),
            'markers' => $this->markers,
            'contracts' => ContractResource::collection($this->contracts),
            'passport' => $this->passport,
            'email' => $this->email,
            'photo' => $this->photo
        ]);
    }
}
