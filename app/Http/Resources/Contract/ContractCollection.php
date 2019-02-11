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
            'year' => $this->year,
            'payment_count' => $this->payments()->count(),
            'client' => new PersonResource($this->client),
            'createdAdmin' => new PersonResource($this->createdAdmin),
            'version' => $this->version,
            'subjects' => $this->subjects,
            'created_at' => $this->created_at,
        ];
    }
}
