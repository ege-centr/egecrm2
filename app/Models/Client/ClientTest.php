<?php

namespace App\Models\Client;

use Illuminate\Database\Eloquent\Model;
use App\Models\{
    Test\Test,
    Client\Client
};

class ClientTest extends Model
{
    public $timestamps = false;
    protected $fillable = ['client_id', 'test_id', 'started_at', 'is_finished_manually'];
    protected $appends = ['results'];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function answers()
    {
        return ClientTestAnswer::where('client_test_answers.client_id', $this->client_id)
            ->join('test_problem_answers', 'test_problem_answers.id', '=', 'client_test_answers.test_problem_answer_id')
            ->join('test_problems', 'test_problems.id', '=', 'test_problem_answers.test_problem_id')
            ->where('test_problems.test_id', $this->test_id);
    }

    /**
     * Тест закончен
     */
    public function getIsFinishedAttribute()
    {
        return $this->is_finished_manually ||
            ($this->started_at !== null && time() - strtotime($this->started_at) >= (60 * $this->test->minutes));
    }

    public function scopeFinished($query, $negate = false)
    {
        return $query->whereRaw(($negate ? 'NOT' : '') . "
        (
            is_finished_manually = 1 OR
            MINUTE(TIMEDIFF(NOW(), started_at)) >= (select minutes from tests where tests.id = client_tests.test_id)
        )");
    }

    public function scopeInProgress($query)
    {
        return $query
            ->whereNotNull("started_at")
            ->finished(true);
    }

    /**
     * Тест в процессе
     */
    public function getIsInProgressAttribute()
    {
        return $this->started_at !== null && ! $this->is_finished;
    }

    public function getResultsAttribute()
    {
        if ($this->is_finished) {
            $answers = $this->answers()->select('score')->get();

            $score = 0;
            foreach ($answers as $answer) {
                $score += $answer->score;
            }

            return [
                'answers' => $answers,
                'score' => $score,
                'max_score' => $this->test->getMaxScore(),
            ];
        }
        return null;
    }
}
