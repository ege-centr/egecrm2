<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonWithEmailResource;

class GroupClientCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'names' => $this->names,
            'default_name' => $this->default_name,
            'branches' => $this->branches,
            'email' => $this->email_string,
            'subject_status' => $this->subject_status,
            'schedule' => $this->schedule,
            'representative' => new PersonWithEmailResource($this->representative),
        ];
    }
}
