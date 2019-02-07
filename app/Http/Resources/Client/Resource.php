<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Contract\ContractResource;
use App\Http\Resources\Group\Collection as GroupCollection;
use App\Http\Resources\Test\ClientTest;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => $this->phones,
            'names' => $this->names,
            // 'requests' => $this->getRequests(),
            // 'payments' => $this->payments,
            'groups' => $this->groups,
            'contracts' => ContractResource::collection($this->contracts),
            'representative' => new Representative($this->representative),
            'tests' => ClientTest::collection($this->tests),
            'email' => $this->email,
            'photo' => $this->photo,
            'headTeacher' => $this->getHeadTeacher(),
        ]);
    }
}
