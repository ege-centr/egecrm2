<?php

namespace App\Http\Resources\Log;

use Illuminate\Http\Resources\Json\JsonResource;

class LogCollection extends JsonResource
{
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
