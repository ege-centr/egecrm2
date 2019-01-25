<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group\GroupAct;
use App\Http\Resources\Group\ActResource;

class GroupActsController extends Controller
{
    protected $filters = [
        'equals' => ['group_id'],
    ];

    public function index(Request $request)
    {
        $query = GroupAct::orderBy('id', 'desc');
        $this->filter($request, $query);
        return ActResource::collection(
            $this->showAll($query)
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
