<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;

class ContractResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'is_active' => $this->isActive(),
            'payments' => $this->payments,
            'subjects' => $this->subjects
        ]);
    }
}
