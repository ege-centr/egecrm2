<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Person\PersonResource;

class AbstractReviewCollection extends Resource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'year', 'subject_id', 'client_id', 'teacher_id', 'lesson_count', 'grade_id',
        ], [
            'review' => new ReviewCollection($this->review),
            'teacher' => new PersonResource($this->teacher),
            'client' => new PersonResource($this->client),
        ]);
    }
}
