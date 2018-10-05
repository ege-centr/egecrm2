<?php

namespace App\Http\Resources\Request;

use App\Http\Resources\Admin\Resource as AdminResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(without(parent::toArray($request), [
            'comment'
        ]), [
            'phones' => $this->phones,
            'responsibleAdmin' => new AdminResource($this->responsibleAdmin)
        ]);
    }
}
