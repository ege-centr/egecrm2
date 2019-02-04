<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\Resource as PersonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        $lesson_count = $this->lessons()->count();
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'subject_id' => $this->subject_id,
            'cabinet' => $this->cabinet,
            'year' => $this->year,
            'teacher' => new PersonResource($this->teacher),
            'clients_count' => count($this->clients),
            'lessons_count' => $lesson_count,
            'lessons_conducted_count' => $this->lessons()->where('status', 'conducted')->count(),
            'first_lesson_date' => $lesson_count ? $this->lessons()->orderBy('date', 'asc')->value('date') : null,
            'schedule' => $this->getSchedule(),
            // 'schedule' => ['label' => '123']
        ];
    }
}
