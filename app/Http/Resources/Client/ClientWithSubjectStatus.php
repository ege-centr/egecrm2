<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonWithEmailResource;

class ClientWithSubjectStatus extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'names' => $this->names,
            'default_name' => $this->default_name,
            'type' => trimModelClass(get_class($this->resource), true),
            'subject_status' => $this->subject_status,
        ]);
    }
}
