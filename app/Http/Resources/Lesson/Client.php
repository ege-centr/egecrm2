<?php

namespace App\Http\Resources\Lesson;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Client\Collection as ClientCollection;

class Client extends JsonResource
{
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'client' => new ClientCollection($this->client),
            'late' => $this->late,
            'is_absent' => $this->is_absent,
            'price' => $this->price,
            'comment' => $this->comment,
        ]);
    }
}
