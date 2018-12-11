<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Light as AdminResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'is_planned' => $this->is_planned,
            'is_conducted' => $this->is_conducted,
        ]);
    }
}
