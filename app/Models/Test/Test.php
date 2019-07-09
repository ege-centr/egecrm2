<?php

namespace App\Models\Test;

use Illuminate\Database\Eloquent\Model;
use App\Models\Client\{Client, ClientTest};

class Test extends Model
{
    protected $fillable = ['title', 'subject_id', 'grade_id', 'minutes'];

    public function problems()
    {
        return $this->hasMany(TestProblem::class);
    }

    public function clientTests()
    {
        return $this->hasMany(ClientTest::class);
    }

    public function getMaxScore()
    {
        $max_score = 0;
        foreach($this->problems as $problem) {
            $problem_max_score = 0;
            foreach($problem->answers as $answer) {
                if ($answer->score > $problem_max_score) {
                    $problem_max_score = $answer->score;
                }
            }
            $max_score += $problem_max_score;
        }
        return $max_score;
    }
}
