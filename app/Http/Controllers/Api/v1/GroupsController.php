<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Group\Group, Lesson};
use App\Http\Resources\Group\{Collection as GroupCollection, Resource as GroupResource};

class GroupsController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::take(100)->orderBy('id', 'desc');

        if (isset($request->year) && $request->year) {
            $query->where('year', $request->year);
        }

        if (isset($request->subject_id) && $request->subject_id) {
            $query->where('subject_id', $request->subject_id);
        }

        if (isset($request->grade_id) && $request->grade_id) {
            $query->where('grade_id', $request->grade_id);
        }

        if (isset($request->group_id) && $request->group_id) {
            $query->where('id', '<>', $request->group_id);
        }

        return GroupCollection::collection($query->get());
    }

    public function store(Request $request)
    {
        $model = Group::create($request->input());
    }

    public function show($id)
    {
        return new GroupResource(Group::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Group::find($id);
        $model->update($request->all());

        return new GroupResource($model);
    }

    public function destroy($id)
    {
        //
    }
}
