<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Teacher, Client\Client };
use App\Traits\HasCreatedEmail;

class Report extends Model
{
    use HasCreatedEmail;

    protected $fillable = [
        'subject_id', 'homework_score', 'activity_score', 'behavior_score',
        'learning_ability_score', 'knowledge_score', 'homework_comment', 'activity_comment',
        'behavior_comment', 'learning_ability_comment', 'knowledge_comment', 'is_available_for_parents',
        'date', 'expected_score_from', 'expected_score_to', 'expected_score_max', 'recommendation',
        'client_id', 'teacher_id', 'year'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
