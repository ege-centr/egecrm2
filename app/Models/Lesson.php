<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'teacher_id', 'cabinet_id', 'date', 'time',
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

    public function getTimeAttribute()
    {
        if ($this->attributes['time']) {
            return mb_strimwidth($this->attributes['time'], 0, 5);
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
