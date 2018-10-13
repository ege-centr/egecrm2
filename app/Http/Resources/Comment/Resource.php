<?php

namespace App\Http\Resources\Comment;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Light as AdminResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'text' => $this->text,
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'created_at' => $this->created_at->toDateTimeString(),
        ]);
    }
}
