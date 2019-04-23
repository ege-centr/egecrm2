<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class TeacherCollection extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'in_egecentr' => $this->in_egecentr,
            'subjects_ec' => $this->subjects_ec,
            'names' => $this->names,
            'default_name' => $this->default_name,
        ];
    }
}
