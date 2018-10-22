<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Group\Group, Journal};
use App\Http\Resources\Group\{Collection as GroupCollection, Resource as GroupResource};

class GroupsController extends Controller
{
    public function index()
    {
        return GroupCollection::collection(Group::paginate(30));
    }

    public function store(Request $request)
    {
        $model = Group::create($request->input());
        foreach ($request->clients as $client) {
            $model->groupClients()->create(['client_id' => $client['id']]);
        }
        $model->lessons()->createMany($request->lessons);
    }

    public function show($id)
    {
        return new GroupResource(Group::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Group::find($id);
        $model->update($request->all());
        $model->groupClients()->delete();
        foreach ($request->clients as $client) {
            $model->groupClients()->create(['client_id' => $client['id']]);
        }

        // @todo
        $model->lessons()->delete();
        $model->lessons()->createMany($request->lessons);

        return new GroupResource($model);
    }

    public function destroy($id)
    {
        //
    }
}
