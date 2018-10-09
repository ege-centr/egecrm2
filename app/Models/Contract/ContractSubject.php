<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Model;

class ContractSubject extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'subject_id',
        'lessons',
        'lessons_planned',
        'status'
    ];
}
