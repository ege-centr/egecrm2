<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Group\Group, Lesson};
use DB;
use App\Http\Resources\Group\{Collection as GroupCollection, Resource as GroupResource};

class GroupsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'teacher_id', 'subject_id', 'grade_id'],
    ];

    public function index(Request $request)
    {
        $query = Group::search();
        $this->filter($request, $query);
        return $query->paginateRaw($request->paginate);
        // $query = Group::with(['lessons'])->orderBy('id', 'desc');

        // $this->filter($request, $query);

        // if (isset($request->group_id) && $request->group_id) {
        //     $query->where('id', '<>', $request->group_id);
        // }

        // if (isset($request->client_id) && $request->client_id) {
        //     $query->whereExists(function ($query) use ($request) {
        //         $query->select(DB::raw(1))
        //             ->from('group_clients')
        //             ->whereRaw('group_clients.group_id = groups.id AND group_clients.client_id = ' . $request->client_id);
        //     });
        // }

        // return GroupCollection::collection($query->paginate(100));
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
