<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;

class TasksController extends Controller
{
    public function index()
    {
        return Task::orderBy('id', 'desc')->get()->all();
    }

    public function store(Request $request)
    {
        $request->merge(['status' => $request->status['value']]);
        return Task::create($request->all());
    }

    public function show($id)
    {
        return new Resource(Admin::find($id));
    }

    public function update(Request $request, $id)
    {
        $request->merge(['status' => $request->status['value']]);
        Task::find($id)->update($request->all());
    }

    public function destroy($id)
    {
        Task::find($id)->delete();
    }
}
