<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;
use App\Http\Resources\Teacher\TeacherCollection;
use App\Http\Resources\Client\ClientWithSubjectStatus;

class LessonResource extends JsonResource
{
    public function toArray($request)
    {
        $clientLessons = $this->clientLessons;
        foreach($clientLessons as &$clientLesson) {
            $clientLesson->client->subject_status = $clientLesson->client->getSubjectStatus($this->group->year, $this->group->subject_id);
        }

        $groupClients = $this->group->clients;
        foreach($groupClients as &$groupClient) {
            $groupClient->subject_status = $groupClient->getSubjectStatus($this->group->year, $this->group->subject_id);
        }

        return array_merge(parent::toArray($request), [
            'groupClients' => ClientWithSubjectStatus::collection($groupClients),
            'createdUser' => new PersonResource($this->createdUser),
            'conductedUser' => new PersonResource($this->conductedUser),
            'clientLessons' => ClientLessonInSchedule::collection($this->clientLessons),
            'teacher' => new TeacherCollection($this->teacher),
        ]);
    }
}
