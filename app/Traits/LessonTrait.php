<?php

namespace App\Traits;

use App\Models\{Group\Group, Cabinet};

trait LessonTrait
{
    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function getTeacherIdAttribute($value)
    {
        if ($this->status === 'conducted') {
            return $value;
        }
        return $this->group->teacher_id;
    }

    public function getClientGradeIdAttribute($value)
    {
        if ($this->status === 'conducted') {
            return $value;
        }
        return $this->group->grade_id;
    }

    public function getGroupGradeIdAttribute($value)
    {
        if ($this->status === 'conducted') {
            return $value;
        }
        return $this->group->grade_id;
    }

    public function getSubjectIdAttribute($value)
    {
        if ($this->status === 'conducted') {
            return $value;
        }
        return $this->group->subject_id;
    }
}
