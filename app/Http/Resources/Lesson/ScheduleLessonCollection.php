<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Light as AdminResource;
use App\Http\Resources\Teacher\Collection as TeacherResource;
use App\Http\Resources\Person\PersonResource;
use App\Models\Lesson\ClientLesson;

class ScheduleLessonCollection extends JsonResource
{
    public function toArray($request)
    {
        // return parent::toArray($request->all());
        return extractFields($this, [
            'id', 'date', 'time', 'cabinet_id', 'status', 'conducted_email_id',
            'created_admin_id', 'created_at', 'teacher_id', 'group',
            'is_first_in_group', 'is_not_registered', 'is_unplanned', 'group_id'
        ], [
            'clientLesson' => $this->client_lesson_id ? ClientLesson::find($this->client_lesson_id) : null,
        ]);
    }
}
