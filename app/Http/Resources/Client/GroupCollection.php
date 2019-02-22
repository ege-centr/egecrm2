<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class GroupCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'names' => $this->names,
            'bars' => $this->getBars(),
            'branches' => $this->branches,
            'email' => $this->email,
            'subject_status' => $this->subject_status,
        ];
    }
}
