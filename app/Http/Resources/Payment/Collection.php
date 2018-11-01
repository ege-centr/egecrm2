<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
