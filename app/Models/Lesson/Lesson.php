<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\{Model, Builder};
use App\Models\{Teacher, Admin\Admin, Client\Client, Group\Group};
use App\Traits\LessonTrait;

class Lesson extends Model
{
    use LessonTrait;

    /**
     * Статусы занятий
     */
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CONDUCTED = 'conducted';
    const STATUS_PLANNED = 'planned';

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

    public function getGradeIdAttribute()
    {
        return $this->attributes['group_grade_id'];
    }


    /**
     * первое занятие в группе?
     */
    public function getIsFirstInGroupAttribute()
    {
        return $this->date === self::where('group_id', $this->group_id)
            ->where('status', '<>', self::STATUS_CANCELLED)
            ->min('date');
    }

    /**
     * занятие не зарегистрировано?
     */
    public function getIsNotRegisteredAttribute()
    {
        return $this->date < now()->format(DATE_FORMAT)
            && $this->date > now()->sub(new \DateInterval('P10D'))
            && $this->status !== self::STATUS_CANCELLED
            && $this->status !== self::STATUS_CONDUCTED;
    }


    public function getClientsCountAttribute()
    {
        if ($this->is_conducted) {
            return $this->clientLessons()->count();
        }
        return $this->group->groupClients()->count();
    }

    // public function addClient($client)
    // {
    //     $data = ;
    //     return
    // }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('lessons', function (Builder $builder) {
			$builder->where(function($query) {
				return $query->where('entity_type', Teacher::class)->orWhereNull('entity_type');
	        });
        });

        static::creating(function ($model) {
            $model->entry_id = uniqid();
            // $model->created_email_id = User::id();
        });
    }
}
