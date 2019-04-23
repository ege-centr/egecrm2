<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;
use App\Http\Resources\Teacher\TeacherCollection;

class ActResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'createdUser' => new PersonResource($this->createdUser),
            'teacher' => new TeacherCollection($this->teacher),
        ]);
    }
}
