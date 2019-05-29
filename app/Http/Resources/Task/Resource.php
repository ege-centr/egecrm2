<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'files' => $this->files,
            'createdUser' => new \PersonResource($this->createdUser)
        ]);
    }
}
