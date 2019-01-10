<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Models\Test\TestProblemAnswer;

class ClientTestAnswer extends Model
{
    public $timestamps = false;
    protected $fillable = ['test_problem_answer_id', 'client_id'];


    public function answer()
    {
        return $this->belongsTo(TestProblemAnswer::class, 'test_problem_answer_id');
    }
}
