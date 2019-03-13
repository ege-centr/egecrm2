<?php

namespace App\Http\Resources\Request;

use App\Http\Resources\Admin\Resource as AdminResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\Resource as CommentResource;
use App\Http\Resources\Phone\PhoneResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'comments' => CommentResource::collection($this->comments),
            'phones' => PhoneResource::collection($this->phones),
            'client_ids' => $this->getClientIds(),
            'responsibleAdmin' => new AdminResource($this->responsibleAdmin),
            'createdAdmin' => new AdminResource($this->createdAdmin),
        ]);
    }
}
