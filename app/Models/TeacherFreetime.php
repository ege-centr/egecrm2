<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherFreetime extends Model
{
    protected $table = 'teacher_freetime';

    public $timestamps = false;

    protected $fillable = ['weekday', 'from', 'to', 'teacher_id'];

    public function getFromAttribute()
    {
        if ($this->attributes['from']) {
            return mb_strimwidth($this->attributes['from'], 0, 5);
        }
    }

    public function getToAttribute()
    {
        if ($this->attributes['to']) {
            return mb_strimwidth($this->attributes['to'], 0, 5);
        }
    }
}
