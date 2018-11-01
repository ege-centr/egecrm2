<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
        ];
        // return array_merge(parent::toArray($request), [
        //
        // ]);
    }
}
