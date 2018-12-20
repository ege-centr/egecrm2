<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Contract\Resource as ContractResource;
use App\Http\Resources\Group\Collection as GroupCollection;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'names' => $this->names,
            'photo_url' => $this->photo_url,
            'groups' => GroupCollection::collection($this->groups),
            'payments' => $this->payments,
        ];
    }
}
