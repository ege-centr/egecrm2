<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonResource;

class ReviewResource extends JsonResource
{
    public function toArray($request)
    {
        $fields = extractFields([
            'id', 'teacher_id', 'client_id', 'subject_id', 'grade_id', 'signature', 'is_approved', 'year',
            'is_published', 'score', 'max_score', 'comments', 'expressive_title'
        ], $this);

        return array_merge($fields, [
            'client' => new PersonResource($this->client),
            'reviewer_admin_id' => $this->client->reviewer_admin_id,
        ]);
    }
}
