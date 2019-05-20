<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Model;
use App\Models\Factory\Subject;


class ContractSubject extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'subject_id',
        'lessons',
        'lessons_planned',
        'status'
    ];

    protected $touches = ['contract'];

    public function getSubjectAttribute()
    {
        return Subject::whereId($this->subject_id)->first();
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }
}
