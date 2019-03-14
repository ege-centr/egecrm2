<?php

namespace App\Http\Resources\Sms;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonWithPhotoResource;

class SmsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'createdUser' => new PersonWithPhotoResource($this->createdUser),
            'is_secret' => $this->is_secret,
        ];
    }
}
