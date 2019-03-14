<?php

namespace App\Http\Resources\Request;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\Resource as CommentResource;
use App\Http\Resources\Phone\PhoneResource;
use PersonResource;
use PersonWithPhotoResource;

class RequestCollection extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'status', 'created_at', 'subjects', 'grade_id', 'branches'
        ], [
            'comments' => CommentResource::collection($this->comments),
            'phones' => PhoneResource::collection($this->phones),
            'client_ids' => $this->getClientIds(),
            'responsibleAdmin' => new PersonResource($this->responsibleAdmin),
            'createdUser' => new PersonWithPhotoResource($this->createdUser),
        ]);
    }
}
