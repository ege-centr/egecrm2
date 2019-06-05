<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Person\PersonResource;

class ReportCollection extends Resource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'homework_score', 'date',
            'learning_ability_score', 'knowledge_score','is_available_for_parents',
            'learning_ability_comment', 'knowledge_comment','homework_comment',
        ]);
    }
}
