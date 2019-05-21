<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Group\Group, Lesson};
use DB;
use App\Http\Resources\Group\{GroupCollection, GroupResource};
use App\Http\Resources\AlgoliaResult;

class GroupsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'teacher_id', 'subject_id', 'grade_id'],
        'equals' => ['client_ids'],
    ];

    public function index(Request $request)
    {
        $query = Group::search()->with([
            'facets' => ['*']
        ]);
        $this->filter($request, $query);
        return new AlgoliaResult($query->paginateRaw($request->paginate ?: SHOW_ALL));
    }

    public function store(Request $request)
    {
        return new GroupResource(
            Group::create($request->input())
        );
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
