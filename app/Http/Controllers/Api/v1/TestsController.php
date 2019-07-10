<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Test\{Test, TestProblem};
use App\Http\Resources\Test\{TestCollection, TestResource};
use App\Http\Requests\Test\{StoreOrUpdateRequest, DestroyRequest};

class TestsController extends Controller
{
    public function index(Request $request)
    {
        $query = Test::query();
        if (isset($request->client_id)) {
            $query->join('client_tests', function ($join) use ($request) {
                $join->on('client_tests.test_id', '=', 'tests.id')
                    ->where('client_tests.client_id', $request->client_id);
            });
        }
        // TODO: ID теста затирается из-за join client_tests
        return TestCollection::collection(Test::whereIn('id', $query->pluck('tests.id'))->get());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreOrUpdateRequest $request)
    {
        $new_model = Test::create($request->input());
        $this->handleTestProblems($new_model, $request->problems);

        return response($new_model, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new TestResource(Test::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreOrUpdateRequest $request, Test $test)
    {
        $test->update($request->all());
        $this->handleTestProblems($test, $request->problems);
        return new TestResource($test);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Test $test, DestroyRequest $request)
    {
        $test->delete();
    }

    private function handleTestProblems(Test $model, $problems)
    {
        foreach($problems as $problem) {
            if (isset($problem['id'])) {
                $test_problem = TestProblem::find($problem['id']);
                $test_problem->update($problem);
                $test_problem->answers()->delete();
                $test_problem->answers()->createMany($problem['answers']);
            } else {
                $new_problem = $model->problems()->create($problem);
                $new_problem->answers()->createMany($problem['answers']);
            }
        }
    }
}
