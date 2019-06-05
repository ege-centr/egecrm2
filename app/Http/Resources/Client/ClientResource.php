<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Contract\ContractResource;
use App\Http\Resources\Group\Collection as GroupCollection;
use App\Http\Resources\{Test\ClientTest, Phone\PhoneResource};
use App\Http\Resources\Photo\PhotoResource;

class ClientResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => PhoneResource::collection($this->phones),
            'names' => $this->names,
            'default_name' => $this->default_name,
            // 'requests' => $this->getRequests(),
            // 'payments' => $this->payments,
            'birthdate' => $this->birthdate,
            'groups' => $this->groups,
            'contracts' => ContractResource::collection($this->contracts),
            'representative' => new Representative($this->representative),
            'tests' => ClientTest::collection($this->tests),
            'email' => $this->email_string,
            'photo' => new PhotoResource($this->photo),
            'headTeacher' => $this->getHeadTeacher(),
            'createdUser' => new \PersonResource($this->createdUser),
            'is_banned' => $this->isBanned(),
        ]);
    }
}
