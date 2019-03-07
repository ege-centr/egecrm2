<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Teacher, Client\Client, Lesson\ClientLesson };

class AbstractReview extends Model
{
    protected $table = 'lessons';

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
        return ClientLesson::where('lessons.entity_id', $this->client_id)
            ->where('lessons.teacher_id', $this->teacher_id)
            ->where('lessons.subject_id', $this->subject_id)
            ->where('lessons.year', $this->year)
            ->count();
    }


    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('abstract-review', function($query) {
            return $query->where('entity_type', Client::class)
                ->leftJoin('reviews', function($join) {
                    $join->on('reviews.client_id', '=', 'lessons.entity_id')
                        ->on('reviews.teacher_id', '=', 'lessons.teacher_id')
                        ->on('reviews.subject_id', '=', 'lessons.subject_id')
                        ->on('reviews.year', '=', 'lessons.year');
                })
                ->selectRaw('
                    reviews.id as review_id, lessons.year, lessons.subject_id, lessons.teacher_id,
                    lessons.client_grade_id as grade_id, lessons.entity_id as client_id
                ')
                ->groupBy('reviews.id', 'lessons.entity_id', 'lessons.teacher_id', 'lessons.subject_id', 'lessons.year');
        });
    }
}
