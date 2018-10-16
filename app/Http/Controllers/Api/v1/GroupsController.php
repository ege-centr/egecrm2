<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group\Group;
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
        foreach ($request->clients as $client_id) {
            $model->clients()->create(compact('client_id'));
        }
    }

    public function show($id)
    {
        return new GroupResource(Group::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Group::find($id);
        $model->update($request->all());
        $model->clients()->delete();
        foreach ($request->clients as $client_id) {
            $model->clients()->create(compact('client_id'));
        }
        return new GroupResource($model);
    }

    public function destroy($id)
    {
        //
    }
}
