<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonWithPhotoResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'createdUser' => new PersonWithPhotoResource($this->createdUser),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
