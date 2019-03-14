<?php

namespace App\Http\Resources\EmailMessage;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\PersonWithPhotoResource;

class EmailMessageResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'files', 'created_at', 'subject', 'message', 'email'
        ], [
            'createdUser' => new PersonWithPhotoResource($this->createdUser),
        ]);
    }
}
