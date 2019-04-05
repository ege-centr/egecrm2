<?php

namespace App\Http\Resources\PaymentAdditional;

use Illuminate\Http\Resources\Json\JsonResource;
use PersonResource;

class PaymentAdditionalResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request->all()), [
            'createdUser' => new PersonResource($this->createdUser)
        ]);
    }
}
