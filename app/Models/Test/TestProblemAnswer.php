<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;

class TestProblemAnswer extends Model
{
    protected $fillable = ['text', 'score'];
    public $timestamps = false;

    public function problem()
    {
        return $this->belongsTo(TestProblem::class, 'test_problem_id');
    }
}
