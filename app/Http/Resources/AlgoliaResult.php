<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AlgoliaResult extends JsonResource
{
    public function toArray($request)
    {
        $item = json_decode(json_encode(parent::toArray($request)));
        // dd($item);
        return [
            'data' => $item->data->hits,
            'facets' => $item->data->facets,
            'meta' => [
                'current_page' => $item->current_page,
                'last_page' => $item->last_page,
            ]
        ];
    }
}
