<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Group\Group, Journal};
use App\Http\Resources\Group\{Collection as GroupCollection, Resource as GroupResource};

class GroupsController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::take(100)->orderBy('id', 'desc');

        foreach(['year', 'grade_id', 'subject_id'] as $field) {
            if (isset($request->{$field})) {
                $query->where($field, $request->{$field});
            }
        }

        return GroupCollection::collection($query->get());
    }

    public function store(Request $request)
    {
        $model = Group::create($request->input());
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

        // TODO
        $model->lessons()->delete();
        $model->lessons()->createMany($request->lessons);

        return new GroupResource($model);
    }

    public function destroy($id)
    {
        //
    }
}
