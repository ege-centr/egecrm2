<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;
use App\Http\Resources\Teacher\Collection as TeacherResource;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'groupClients' => PersonResource::collection($this->group->clients),
            'createdUser' => new PersonResource($this->createdUser),
            'conductedUser' => new PersonResource($this->conductedUser),
            'clientLessons' => ClientLessonInSchedule::collection($this->clientLessons),
            'teacher' => new TeacherResource($this->teacher),
        ]);
    }
}
