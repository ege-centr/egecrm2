<?php

namespace App\Http\Resources\Log;

use Illuminate\Http\Resources\Json\JsonResource;
use PersonResource;

class LogCollection extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request->all), [
            'createdUser' => new PersonResource($this->createdUser),
            'previewModeUser' => $this->previewModeEmail === null ? null : new PersonResource($this->previewModeEmail->user),
        ]);
    }
}
