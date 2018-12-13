<?php

namespace App\Http\Resources\Request;

use App\Http\Resources\Admin\Resource as AdminResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Comment\Resource as CommentResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(without(parent::toArray($request), [
            'comment'
        ]), [
            'comments' => CommentResource::collection($this->comments),
            'phones' => $this->phones,
            'responsibleAdmin' => new AdminResource($this->responsibleAdmin)
        ]);
    }
}
