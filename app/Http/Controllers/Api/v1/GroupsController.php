<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Group\Group, Lesson};
use DB;
use App\Http\Resources\Group\{Collection as GroupCollection, Resource as GroupResource};

class GroupsController extends Controller
{
    public function index(Request $request)
    {
        $query = Group::orderBy('id', 'desc');

        if (isset($request->year) && $request->year) {
            $query->whereIn('year', explode(',', $request->year));
        }

        if (isset($request->teacher_id) && $request->teacher_id) {
            $query->whereIn('teacher_id', explode(',', $request->teacher_id));
        }

        if (isset($request->subject_id) && $request->subject_id) {
            $query->whereIn('subject_id', explode(',', $request->subject_id));
        }

        if (isset($request->grade_id) && $request->grade_id) {
            $query->whereIn('grade_id', explode(',', $request->grade_id));
        }

        if (isset($request->branch_id) && $request->branch_id) {
            // $query->where('branch_id', $request->branch_id);
        }

        if (isset($request->group_id) && $request->group_id) {
            $query->where('id', '<>', $request->group_id);
        }

        if (isset($request->client_id) && $request->client_id) {
            $query->whereExists(function ($query) use ($request) {
                $query->select(DB::raw(1))
                    ->from('group_clients')
                    ->whereRaw('group_clients.group_id = groups.id AND group_clients.client_id = ' . $request->client_id);
            });
        }

        return GroupCollection::collection($query->paginate(100));
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
