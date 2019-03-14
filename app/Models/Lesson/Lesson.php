<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Group\Group, Cabinet, Teacher, Admin\Admin, Email};
use App\Traits\HasCreatedEmail;

class Lesson extends Model
{
    use HasCreatedEmail;

    /**
     * Статусы занятий
     */
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CONDUCTED = 'conducted';
    const STATUS_PLANNED = 'planned';

    protected $fillable = [
        'teacher_id', 'cabinet_id', 'date', 'time', 'price',
        'status', 'is_unplanned', 'group_id'
    ];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function clientLessons()
    {
        return $this->hasMany(ClientLesson::class);
    }

    public function getTimeAttribute()
    {
        if ($this->attributes['time']) {
            return mb_strimwidth($this->attributes['time'], 0, 5);
        }
    }

    public function getConductedUserAttribute()
    {
        return Email::getUser($this->conducted_email_id);
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
}
