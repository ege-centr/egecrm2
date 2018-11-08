<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Payment, Client\Client, Contract\Contract};
use App\Http\Resources\Client\{Resource, Collection};

class ClientsController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->get_all) && $request->get_all) {
            return resourceCollection(Client::orderByName()->get(), Collection::class);
        }
        return resourceCollection(Client::orderBy('id', 'desc')->paginate(30), Collection::class);
    }

    public function store(Request $request)
    {
        $new_model = Client::create($request->input());
        $new_model->phones()->createMany($request->phones);
        $new_model->markers()->createMany($request->markers);
        $new_model->passport()->create($request->passport);
        $new_model->email()->create($request->email);

        return response($new_model->id, 201);
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

        $model->markers()->delete();
        $model->markers()->createMany($request->markers);

        $model->passport()->update($request->passport);
        $model->email()->update($request->email);

        return new Resource($model);
    }

    public function destroy($id)
    {
        //
    }
}
