<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonWithEmailResource;

class GroupCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'names' => $this->names,
            'default_name' => $this->default_name,
            'bars' => $this->getBars(),
            'branches' => $this->branches,
            'email' => $this->email_string,
            'subject_status' => $this->subject_status,
            'representative' => new PersonWithEmailResource($this->representative),
        ];
    }
}
