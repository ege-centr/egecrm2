<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Teacher\Collection as TeacherCollection;

class LessonCollection extends JsonResource
{
    public function toArray($request)
    {
        return extractFields([
            'id', 'date', 'time', 'cabinet_id', 'status', 'conducted_email_id',
            'created_admin_id', 'created_at', 'teacher_id', 'group_id', 'clients_count',
        ], $this);
    }
}
