<?php

namespace App\Http\Resources\Contract;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;

class ContractCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'number' => $this->number,
            'date' => $this->date,
            'sum' => $this->sum,
            'grade_id' => $this->grade_id,
            'discount' => $this->discount,
            'year' => $this->year,
            'client' => new PersonResource($this->client),
            'createdUser' => new PersonResource($this->createdUser),
            'version' => $this->version,
            'is_active' => $this->is_active,
            'subjects' => $this->subjects,
            'created_at' => $this->created_at,
        ];
    }
}
