<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Person\{PersonResource, PersonWithPhotoResource};

class ReportResource extends JsonResource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'id', 'subject_id', 'homework_score', 'activity_score', 'behavior_score',
            'learning_ability_score', 'knowledge_score', 'homework_comment', 'activity_comment',
            'behavior_comment', 'learning_ability_comment', 'knowledge_comment', 'is_available_for_parents',
            'date', 'expected_score_from', 'expected_score_to', 'expected_score_max', 'recommendation', 'year',
            'created_at'
        ], [
            'createdUser' => new PersonResource($this->createdUser),
            'teacher' => new PersonWithPhotoResource($this->teacher),
            'client' => new PersonResource($this->client),
        ]);
    }
}
