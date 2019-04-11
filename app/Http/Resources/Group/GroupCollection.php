<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;
use App\Models\Group\GroupClient;

class GroupCollection extends JsonResource
{
    public function toArray($request)
    {
        $lessons = collect($this->lessons()->notCancelled()->get());
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'subject_id' => $this->subject_id,
            'year' => $this->year,
            'teacher_id' => $this->teacher_id,
            'client_ids' => GroupClient::where('group_id', $this->id)->pluck('client_id'),
            'lessons_count' => $lessons->count(),
            'lessons_conducted_count' => $lessons->where('status', 'conducted')->count(),
            'first_lesson_date' => $lessons->count() > 0 ? $lessons->sortBy('date')->first()->date : null,
            'schedule' => $this->getSchedule(),
        ];
    }
}
