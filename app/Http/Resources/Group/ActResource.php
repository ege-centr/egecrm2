<?php

namespace App\Http\Resources\Group;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Light as AdminResource;
use App\Http\Resources\Teacher\Collection as TeacherResource;

class ActResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'teacher' => new TeacherResource($this->teacher),
        ]);
    }
}
