<?php

namespace App\Http\Resources\Journal;

use Illuminate\Http\Resources\Json\JsonResource;

class Collection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [

        ]);
    }
}
