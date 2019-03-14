<?php

namespace App\Http\Resources\Person;

use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'names' => $this->names,
            'default_name' => $this->default_name,
            'type' => trimModelClass(get_class($this->resource), true),
        ]);
    }
}
