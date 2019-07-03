<?php

namespace App\Http\Resources\Client;

use App\Http\Resources\Photo\PhotoResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientForPhotoCollection extends JsonResource
{
    public function toArray($request)
    {
        $reviews = [];
        foreach($this->reviews as $review) {
            $finalComment = $review->comments->where('type', 'final')->first();
            if ($finalComment) {
                $reviews[] = [
                    'rating' => $finalComment->rating,
                    'is_published' => $review->is_published,
                ];
            }
        }

        return [
            'id' => $this->id,
            'default_name' => $this->default_name,
            'photo' => new PhotoResource($this->photo),
            'reviews' => $reviews,
        ];
    }
}
