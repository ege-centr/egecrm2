<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialDate extends Model
{
    protected $fillable = ['year', 'date', 'subject_id', 'grade_id', 'type'];
    public $timestamps = false;
}
