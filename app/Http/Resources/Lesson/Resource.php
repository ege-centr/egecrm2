<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Light as AdminResource;
use App\Http\Resources\Teacher\Collection as TeacherResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'clientLessons' => Client::collection($this->clientLessons),
            'teacher' => new TeacherResource($this->teacher),
        ]);
    }
}
