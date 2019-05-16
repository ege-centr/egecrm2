<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Photo\PhotoResource;

/**
 * TODO: Сделать общую модель залогинненого пользователя
 */
class UserWithPhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'photo' => new PhotoResource($this->photo)
        ]);
    }
}
