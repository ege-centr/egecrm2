<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientLessonCollection extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'grade_id', 'comment', 'is_absent', 'price', 'late'
        ], [
           'lesson' => new LessonCollection($this->lesson),
        ]);
    }
}
