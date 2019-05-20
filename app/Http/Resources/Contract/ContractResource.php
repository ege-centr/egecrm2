<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;
use PersonResource;

class ContractResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'is_active' => $this->is_active,
            'payments' => $this->payments,
            'subjects' => $this->subjects,
            'createdUser' => new PersonResource($this->createdUser),
        ]);
    }
}
