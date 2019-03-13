<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Client\Client};

class ClientLesson extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'price', 'late', 'is_absent', 'comment',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeJoinLessons($query)
    {
        return $query->join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');
    }
}
