<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'subject_id' => $this->subject_id,
            'cabinet' => $this->cabinet,
            'teacher_name' => $this->teacher->names->abbreviation,
            'clients_count' => count($this->clients),
            'lessons_count' => count($this->lessons) 
        ];
    }
}
