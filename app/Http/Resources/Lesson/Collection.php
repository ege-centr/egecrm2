<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Teacher\Collection as TeacherCollection;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            // 'teacher' => new TeacherCollection($this->teacher),
        ]);
    }
}
