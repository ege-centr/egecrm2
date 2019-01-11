<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group\GroupAct;
use App\Http\Resources\Group\ActResource;

class GroupActsController extends Controller
{
    public function index(Request $request)
    {
        return ActResource::collection(GroupAct::where('group_id', $request->group_id)
            ->orderBy('id', 'desc')
            ->get()
        );
    }

    public function store(Request $request)
    {
        $model = GroupAct::create($request->input());
        return $model;
    }

    public function show($id)
    {
        return GroupAct::find($id);
    }

    public function update(Request $request, $id)
    {
        GroupAct::find($id)->update($request->all());
    }

    public function destroy($id)
    {
        GroupAct::find($id)->delete();
    }
}
