<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Teacher\Resource as TeacherResource;
use App\Http\Resources\Client\GroupCollection as ClientCollection;
use App\Http\Resources\Lesson\LessonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'cabinet' => $this->cabinet,
            'clients' => ClientCollection::collection($this->clients),
            'lessons' => LessonResource::collection($this->lessons),
            'teacher' => new TeacherResource($this->teacher),
            'schedule' => $this->getSchedule()
        ]);
    }
}
