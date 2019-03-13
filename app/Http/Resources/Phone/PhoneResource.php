<?php

namespace App\Http\Resources\Phone;

use Illuminate\Http\Resources\Json\JsonResource;

class PhoneResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'phone_clean' => $this->phone_clean,
            'comment' => $this->comment
        ];
    }
}
