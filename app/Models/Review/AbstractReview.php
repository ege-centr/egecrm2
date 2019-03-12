<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Teacher, Client\Client, Lesson\ClientLesson };

class AbstractReview extends Model
{
    protected $table = 'client_lessons';

    protected $with = ['review'];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function getLessonCountAttribute()
    {
        return ClientLesson::join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->where('client_lessons.client_id', $this->client_id)
            ->where('lessons.teacher_id', $this->teacher_id)
            ->where('groups.subject_id', $this->subject_id)
            ->where('groups.year', $this->year)
            ->count();
    }


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('abstract-review', function($query) {
            return $query->join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
                ->join('groups', 'groups.id', '=', 'lessons.group_id')
                ->leftJoin('reviews', function($join) {
                    $join->on('reviews.client_id', '=', 'client_lessons.client_id')
                        ->on('reviews.teacher_id', '=', 'lessons.teacher_id')
                        ->on('reviews.subject_id', '=', 'groups.subject_id')
                        ->on('reviews.year', '=', 'groups.year');
                })
                ->selectRaw('
                    reviews.id as review_id, groups.year, groups.subject_id, lessons.teacher_id,
                    client_lessons.grade_id, client_lessons.client_id
                ')
                ->groupBy('reviews.id', 'client_lessons.client_id', 'lessons.teacher_id', 'groups.subject_id', 'groups.year');
        });
    }
}
