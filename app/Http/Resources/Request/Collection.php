<?php

namespace App\Http\Resources\Request;

use App\Http\Resources\User\Resource as UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(without(parent::toArray($request), [
            'comment'
        ]), [
            'phones' => $this->phones,
            'responsibleUser' => new UserResource($this->responsibleUser)
        ]);
    }
}
