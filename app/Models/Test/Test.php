<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $fillable = ['title', 'subject_id', 'grade_id'];

    public function problems()
    {
        return $this->hasMany(TestProblem::class);
    }
}
