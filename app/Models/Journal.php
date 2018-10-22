<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'journal';
    protected $fillable = [
        'teacher_id', 'cabinet_id', 'lesson_date', 'lesson_time', 
        'is_cancelled', 'is_unplanned'
    ];

    public function getLessonTimeAttribute()
    {
        if ($this->attributes['lesson_time']) {
            return mb_strimwidth($this->attributes['lesson_time'], 0, 5);
        }
    }
}
