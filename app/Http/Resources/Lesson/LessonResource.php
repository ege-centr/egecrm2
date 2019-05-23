<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;
use App\Http\Resources\Teacher\TeacherCollection;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        $clientLessons = $this->clientLessons;
        foreach($clientLessons as &$clientLesson) {
            $clientLesson->client->subject_status = $clientLesson->client->getSubjectStatus($this->group->year, $this->group->subject_id);
        }

        return array_merge(parent::toArray($request), [
            'groupClients' => PersonResource::collection($this->group->clients),
            'createdUser' => new PersonResource($this->createdUser),
            'conductedUser' => new PersonResource($this->conductedUser),
            'clientLessons' => ClientLessonInSchedule::collection($this->clientLessons),
            'teacher' => new TeacherCollection($this->teacher),
        ]);
    }
}
