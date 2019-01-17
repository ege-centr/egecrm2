<?php

namespace App\Http\Resources\Test;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientTest extends JsonResource
{
    public function toArray($request)
    {
        $fields = [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'test_id' => $this->test_id,
            'results' => $this->results,
            'started_at' => $this->started_at,
        ];

        if (isset($request->includeTest)) {
            $fields['test'] = new Collection($this->test);
        }

        return $fields;
    }
}
