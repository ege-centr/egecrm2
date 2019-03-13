<?php

namespace App\Http\Resources\Sms;

use Illuminate\Http\Resources\Json\JsonResource;

class SmsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'is_secret' => $this->is_secret,
        ];
    }
}
