<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientLessonCollection extends JsonResource
{
    public function toArray($request)
    {
        return extractFields([
            'id', 'group_id', 'cabinet_id', 'teacher_id', 'client_grade_id',
            'status', 'teacher_id', 'date', 'time', 'subject_id', 'is_absent'
        ], $this);
    }
}
