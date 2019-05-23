<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Teacher\TeacherResource;
use App\Http\Resources\Lesson\LessonResource;
use App\Utils\Schedule;

class GroupResource extends JsonResource
{
    public function toArray($request)
    {
        $clients = $this->clients;
        foreach($clients as &$client) {
            $client->subject_status = $client->getSubjectStatus($this->year, $this->subject_id);
            $client->schedule = Schedule::get([
                'group_id' => $client->groups()->pluck('id')->all()
            ], $this->id);
        }

        return array_merge(parent::toArray($request), [
            'clients' => GroupClientCollection::collection($clients),
            'lessons' => LessonResource::collection($this->lessons),
            'teacher' => new TeacherResource($this->teacher),
            'schedule' => Schedule::get(['group_id' => $this->id], $this->id)
        ]);
    }
}
