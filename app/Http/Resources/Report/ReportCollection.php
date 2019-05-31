<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Person\PersonResource;

class ReportCollection extends Resource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'homework_score',
            'learning_ability_score', 'knowledge_score', 'date', 'is_available_for_parents'
        ]);
    }
}
