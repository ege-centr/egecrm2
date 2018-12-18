<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Contract\Resource as ContractResource;
use App\Http\Resources\Group\Collection as GroupCollection;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => $this->phones,
            'name' => getName($this),
            'requests' => $this->getRequests(),
            'payments' => $this->payments,
            'contracts' => ContractResource::collection($this->contracts),
            'groups' => GroupCollection::collection($this->groups),
            'representative' => new Representative($this->representative),
            'email' => $this->email,
            'photo' => $this->photo
        ]);
    }
}
