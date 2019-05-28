<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'attachments' => $this->attachments ?? [],
            'createdUser' => new \PersonResource($this->createdUser)
        ]);
    }
}
