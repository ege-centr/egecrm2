<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientLessonCollection extends JsonResource
{
    public function toArray($request)
    {
        return extractFields([
            'id', 'group_id', 'cabinet_id', 'teacher_id', 'grade_id', 'comment',
            'status', 'date', 'time', 'subject_id', 'is_absent', 'price'
        ], $this);
    }
}
