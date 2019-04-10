<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedEmail;

class GroupAct extends Model
{
    use HasCreatedEmail;

    protected $fillable = [
        'group_id', 'sum', 'lesson_count', 'teacher_id', 'date', 'date_from', 'date_to'
    ];
}
