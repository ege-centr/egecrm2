<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Request as ClientRequest;
use App\Http\Resources\Request\{Resource, Collection};

class RequestsController extends Controller
{
    public function index(Request $request)
    {
        $query = ClientRequest::with('responsibleAdmin')->orderBy('id', 'desc');
        if (isset($request->status)) {
            $query->whereStatus($request->status);
        }
        if (isset($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        return resourceCollection($query->paginate(30), Collection::class);
    }

    public function store(Request $request)
    {
        $new_model = ClientRequest::create($request->input());
        $new_model->phones()->createMany($request->phones);
        return response($new_model, 201);
    }

    public function show($id)
    {
        return new Resource(ClientRequest::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = ClientRequest::find($id);
        $model->update($request->input());

        $model->phones()->delete();
        $model->phones()->createMany($request->phones);

        return response()->json(null, 204);
    }

    public function destroy($id)
    {
        //
    }
}
