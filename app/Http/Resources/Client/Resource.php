<?php

namespace App\Http\Resources\Client;

use Illuminate\Http\Resources\Json\JsonResource;

class Resource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'phones' => $this->phones,
            'student_full_name' => implode(' ', [
                $this->student_first_name,
                $this->student_last_name,
                $this->student_middle_name
            ]),
            'representative_full_name' => implode(' ', [
                $this->representative_first_name,
                $this->representative_last_name,
                $this->representative_middle_name
            ]),
            'requests' => $this->getRequests(),
            'passport' => $this->passport,
            'email' => $this->email,
            'photo' => $this->photo
        ]);
    }
}
