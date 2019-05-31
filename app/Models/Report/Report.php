<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Teacher, Client\Client };
use App\Traits\HasAbstract;
use User;

class Report extends Model
{
    use HasAbstract;

    const CATEGORIES = [
        'homework' => 'Выполнение домашнего задания',
        'learning_ability' => 'Способность усваивать новый материал',
        'knowledge' => 'Выполнение контрольных работ, текущий уровень знаний',
    ];

    protected $fillable = [
        'subject_id', 'homework_score',
        'learning_ability_score', 'knowledge_score', 'homework_comment',
        'learning_ability_comment', 'knowledge_comment', 'is_available_for_parents',
        'date', 'expected_score_from', 'expected_score_to', 'expected_score_max', 'recommendation',
        'client_id', 'teacher_id', 'year', 'is_not_moderated', 'price'
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getAbstractAttribute()
    {
        return (object)[
            'client_id' => $this->client_id,
            'subject_id' => $this->subject_id,
            'teacher_id' => $this->teacher_id,
            'year' => $this->year,
            'report_id' => $this->id,
            'report_date' => $this->date,
        ];
    }
}
