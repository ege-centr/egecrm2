<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;

class GroupAct extends Model
{
    use HasCreatedAdmin;

    protected $fillable = ['group_id', 'sum', 'lesson_count', 'teacher_id', 'date'];
}
