<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\{Model, Builder};
use App\Models\{Teacher, Admin\Admin, Client\Client};

class Lesson extends Model
{
    protected $fillable = [
        'teacher_id', 'cabinet_id', 'date', 'time', 'price',
        'status', 'is_unplanned', 'group_id', 'year'
    ];

    protected $attributes = [
        'duration' => 135
    ];

    public function createdAdmin()
    {
        return $this->belongsTo(Admin::class, 'conducted_email_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function clientLessons()
    {
        return $this->hasMany(ClientLesson::class, 'entry_id', 'entry_id');
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

        static::addGlobalScope('lessons', function (Builder $builder) {
            $builder->where('entity_type', '<>', Client::class);
        });

        // static::creating(function ($model) {
        //     $model->created_email_id = User::id();
        // });
    }
}
