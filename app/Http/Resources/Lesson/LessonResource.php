<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Light as AdminResource;
use App\Http\Resources\Teacher\Collection as TeacherResource;
use App\Http\Resources\Person\PersonResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'groupClients' => PersonResource::collection($this->group->clients),
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'clientLessons' => ClientLessonInSchedule::collection($this->clientLessons),
            'teacher' => new TeacherResource($this->teacher),
        ]);
    }
}
