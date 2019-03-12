<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Client\Client};

class ClientLesson extends Model
{
    protected $fillable = [
        'price', 'late', 'is_absent', 'comment',
    ];

    // protected $with = ['client'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeWithJoins($query)
    {
        return $query->join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            // ->selectRaw('
            //     client_lessons.*, groups.year, groups.subject_id, lessons.cabinet_id, lessons.group_id,
            //     lessons.teacher_id, lessons.status, lessons.date, lessons.time, lessons.teacher_id
            // ')
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');
    }
}
