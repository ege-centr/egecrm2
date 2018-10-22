<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Contract\Resource as ContractResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'names' => $this->names,
            'photo_url' => $this->photo_url
        ];
    }
}
