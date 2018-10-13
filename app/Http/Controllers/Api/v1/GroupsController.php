<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group\Group;

class GroupsController extends Controller
{
    public function index()
    {
        return Group::paginate(30);
    }

    public function store(Request $request)
    {
        $new_model = Group::create($request->input());
        $new_model->subjects()->createMany($request->subjects);
        $new_model->payments()->createMany($request->payments);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
