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
        $clients = $this->clients;
        foreach($clients as &$client) {
            $client->subject_status = $client->getSubjectStatus($this->year, $this->grade_id, $this->subject_id);
        }
        return array_merge(parent::toArray($request), [
            'clients' => ClientCollection::collection($clients),
            'lessons' => LessonResource::collection($this->lessons),
            'teacher' => new TeacherResource($this->teacher),
            'schedule' => $this->getSchedule()
        ]);
    }
}
