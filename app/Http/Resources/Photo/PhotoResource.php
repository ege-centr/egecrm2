<?php

namespace App\Http\Resources\Photo;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
{
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'url' => $this->url,
            'url_version' => $this->url_version,
            'url_original' => $this->url_original,
            'url_cropped' => $this->url_cropped,
        ]);
    }
}
