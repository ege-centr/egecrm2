<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\ClientTestAnswer;
use User;

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
        ClientTestAnswer::where([
            ['client_id', $request->client_id],
            ['test_problem_answer_id', $request->test_problem_answer_id]
        ])->delete();
        return ClientTestAnswer::create($request->all());
    }
}
