<?php

namespace App\Http\Resources\Review;

use Illuminate\Http\Resources\Json\JsonResource;

class ReviewCollection extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id',  'is_approved', 'is_published', 'score', 'max_score', 'comments',
        ]);
    }
}
