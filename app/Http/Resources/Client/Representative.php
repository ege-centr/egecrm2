<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Email;

class Representative extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'names' => $this->names,
            'phones' => $this->phones,
            'email' => $this->email ?? new Email,
        ]);
    }
}
