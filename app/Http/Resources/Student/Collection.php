<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => implode(' ', [$this->last_name, $this->first_name])
        ];
    }
}
