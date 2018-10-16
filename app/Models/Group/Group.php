<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Cabinet, Teacher};

class Group extends Model
{
    protected $fillable = [
        'teacher_id', 'head_teacher_id', 'subject_id', 'grade_id', 'teacher_price',
        'duration', 'year', 'is_archived', 'is_ready_to_start', 'cabinet_id'
    ];

    public function clients()
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
}
