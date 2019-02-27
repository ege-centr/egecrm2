<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;

class ClientReviewCollection extends JsonResource
{
    public function toArray($request)
    {
       return extractFields([
           'subject_id', 'grade_id', 'lesson_count', 'teacher_id', 'year', 'client_id', 'review'
        ], $this);
    }
}
