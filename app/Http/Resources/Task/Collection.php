<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\Resource as CommentResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'files' => $this->files,
            'comments' => CommentResource::collection($this->comments),
            'createdUser' => new \PersonResource($this->createdUser),
        ]);
    }
}
