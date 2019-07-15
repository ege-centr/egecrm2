<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\ClientTest;
use App\Http\Resources\Test\ClientTest as ClientTestResource;
use App\Events\ClientTestDestroyed;
use User;

class ClientTestsController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientTest::orderBy('id', 'desc');

        if (isset($request->client_id)) {
            $query->where('client_id', $request->client_id);
        }

        if (isset($request->test_id)) {
            $query->where('test_id', $request->test_id);
        }

        if (isset($request->started)) {
            $query->whereNotNull('started_at');
        }

        if (isset($request->includeTest)) {
            $query->with(['test']);
        }

        if (isset($request->status)) {
            // не начинал
            if ($request->status === 'is_not_started') {
                $query->whereNull('started_at');
            }

             // в процессе
             if ($request->status == 'is_in_progress') {
                $query->inProgress();
            }

            // закончил
            if ($request->status == 'is_finished') {
                $query->finished();
            }
        }

        return ClientTestResource::collection($this->showBy($request, $query));
    }

    public function store(Request $request)
    {
        return new ClientTestResource(
            ClientTest::create($request->all())
        );
    }

    public function update($id, Request $request)
    {
        $model = ClientTest::where([
            ['client_id', User::id()],
            ['test_id', $id]
        ])
        ->firstOrFail();

        $model->update($request->all());

        return new ClientTestResource($model);
    }

    // $id – test id
    public function show($id, Request $request)
    {
        $query = ClientTest::where('test_id', $id);

        if (isset($request->client_id)) {
            $query->where('client_id', $request->client_id);
        }

        if (isset($request->started)) {
            $query->whereNotNull('started_at');
        }

        return new ClientTestResource($query->firstOrFail());
    }

    public function destroy($id)
    {
        $client_test = ClientTest::find($id);
        if ($client_test->is_in_progress) {
            event(new ClientTestDestroyed($client_test));
        }
        $client_test->answers()->delete();
        $client_test->delete();
        return response(null, 204);
    }
}
