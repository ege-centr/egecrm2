<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Email;
use App\Http\Resources\Phone\PhoneResource;

class Representative extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'names' => $this->names,
            'default_name' => $this->default_name,
            'phones' => PhoneResource::collection($this->phones),
            'email' => $this->email_string,
        ]);
    }
}
