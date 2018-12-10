<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    protected $table = 'journal';
    protected $fillable = [
        'teacher_id', 'cabinet_id', 'lesson_date', 'lesson_time',
        'is_cancelled', 'is_unplanned', 'group_id', 'year'
    ];

    protected $attributes = [
        'duration' => 135
    ];

    public function getIsPlannedAttribute()
    {
        return $this->entity_type ? false : true;
    }

    public function getIsConductedAttribute()
    {
        return !$this->is_planned;
    }

    public function createdAdmin()
    {
        return $this->belongsTo(Admin\Admin::class, 'created_email_id');
    }

    public function getLessonTimeAttribute()
    {
        if ($this->attributes['lesson_time']) {
            return mb_strimwidth($this->attributes['lesson_time'], 0, 5);
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_email_id = User::id();
        });
    }
}
