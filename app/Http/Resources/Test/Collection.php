<?php

namespace App\Http\Resources\Test;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'subject_id' => $this->subject_id,
            'title' => $this->title,
            'problems_count' => count($this->problems)
        ];
    }
}
