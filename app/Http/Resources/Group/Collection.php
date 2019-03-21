<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        $lessons = collect($this->lessons);
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'subject_id' => $this->subject_id,
            'cabinet' => $this->cabinet,
            'year' => $this->year,
            'teacher' => new PersonResource($this->teacher),
            'client_ids' => $this->client_ids,
            'lessons_count' => $lessons->count(),
            'lessons_conducted_count' => $lessons->where('status', 'conducted')->count(),
            'first_lesson_date' => $lessons->count() > 0 ? $lessons->sortBy('date')->first()->date : null,
            'schedule' => $this->getSchedule(),
            // 'schedule' => ['label' => '123']
        ];
    }
}
