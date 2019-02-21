<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\{ClientTest, ClientTestAnswer};
use User;
use DB;

class ClientTestAnswersController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientTestAnswer::with(['answer']);

        if (isset($request->client_id)) {
            $query->where('client_id', $request->client_id);
        }

        $result = [];
        $answers = $query->get();
        foreach($answers as $answer) {
            $result[$answer->answer->test_problem_id] = $answer->test_problem_answer_id;
        }

        return $result;
    }

    public function store(Request $request)
    {
        $clientTest = ClientTest::find($request->client_test_id);

        // не принимаем ответы, если тест уже завершен
        if ($clientTest->is_finished) {
            return response(null, 403);
        }

        // удаляем все предыдущие ответы на этот вопрос
        $test_problem_answer_ids = DB::select("SELECT id FROM test_problem_answers
            WHERE test_problem_id = (
                SELECT tp.id FROM test_problem_answers tpa
                JOIN test_problems tp ON tp.id = tpa.test_problem_id
                WHERE tpa.id = {$request->test_problem_answer_id}
            )
        ");
        ClientTestAnswer::where('client_id', $request->client_id)
            ->whereIn('test_problem_answer_id', collect($test_problem_answer_ids)->pluck('id'))
            ->delete();

        return ClientTestAnswer::create($request->all());
    }
}
