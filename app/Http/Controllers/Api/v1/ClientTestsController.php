<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\ClientTest;
use User;

class ClientTestsController extends Controller
{
    // public function index(Request $request)
    // {
    //     $query = ClientTest::query();

    //     if (isset($request->client_id)) {
    //         $query->where('client_id', $request->client_id);
    //     }

    //     if (isset($request->test_id)) {
    //         $query->where('test_id', $request->test_id);
    //     }

    //     if (isset($request->started)) {
    //         $query->whereNotNull('started_at');
    //     }

    //     return $query->get();
    // }

    public function store(Request $request)
    {
        return ClientTest::create($request->all());
    }

    public function update($id, Request $request)
    {
        $model = ClientTest::where([
            ['client_id', User::id()],
            ['test_id', $id]
        ])->first();
        $model->started_at = now();
        $model->save();
        return $model;
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

        return $query->first();
    }
}
