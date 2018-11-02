<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Cabinet, Teacher, Journal, Client\Client};

class Group extends Model
{
    protected $fillable = [
        'teacher_id', 'head_teacher_id', 'subject_id', 'grade_id', 'teacher_price',
        'duration', 'year', 'is_archived', 'is_ready_to_start', 'cabinet_id', 'level'
    ];

    public function clients()
    {
        return $this->hasManyThrough(Client::class, GroupClient::class, 'group_id', 'id', 'id', 'client_id');
    }

    public function groupClients()
    {
        return $this->hasMany(GroupClient::class);
    }

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function lessons()
    {
        return $this->hasMany(Journal::class);
    }

    public function getSchedule()
    {

    }
}
