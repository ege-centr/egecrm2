<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Http\Resources\Client\{Resource, Collection};

class ClientsController extends Controller
{
    public function index()
    {
        return resourceCollection(Client::orderBy('id', 'desc')->paginate(30), Collection::class);
    }

    public function store(Request $request)
    {
        $new_model = Client::create($request->input());
        $new_model->phones()->createMany($request->phones);
        $new_model->passport()->create($request->passport);
        $new_model->email()->create($request->email);

        return response($new_model, 201);
    }

    public function show($id)
    {
        return new Resource(Client::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Client::find($id);
        $model->update($request->input());

        $model->phones()->delete();
        $model->phones()->createMany($request->phones);

        $model->passport()->update($request->passport);
        $model->email()->update($request->email);

        return response()->json(null, 204);
    }

    public function destroy($id)
    {
        //
    }
}
