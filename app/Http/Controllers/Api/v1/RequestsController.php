<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Client\Client, Request as ClientRequest};
use App\Http\Resources\Request\{Resource, Collection};

class RequestsController extends Controller
{
    protected $filters = [
        'equals' => ['status', 'grade_id', 'responsible_admin_id', 'created_admin_id'],
        'interval' => ['created_at'],
    ];

    public function index(Request $request)
    {
        if (isset($request->client_id)) {
            return Collection::collection(
                $this->showAll(Client::find($request->client_id)->requests())
            );
        }
        $query = ClientRequest::with('responsibleAdmin')->orderBy('id', 'desc');
        $this->filter($request, $query);
        return Collection::collection($this->showBy($request, $query));
    }

    public function store(Request $request)
    {
        $new_model = ClientRequest::create($request->input());
        // dd($request->phones);
        $new_model->phones()->createMany($request->phones);
        return response(new Resource($new_model), 201);
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
        ClientRequest::find($id)->delete();
    }
}
