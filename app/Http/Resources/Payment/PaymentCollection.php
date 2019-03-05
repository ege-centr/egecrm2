<?php

namespace App\Http\Resources\Payment;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Person\PersonResource;

class PaymentCollection extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'category', 'type', 'method', 'date', 'sum', 'year',
            'created_admin_id', 'created_at', 'class_name', 'is_confirmed'
        ], [
            'entity' => new PersonResource($this->entity),
        ]);
    }
}
