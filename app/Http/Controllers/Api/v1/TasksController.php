<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Resources\Task\Resource as TaskResource;

class TasksController extends Controller
{
    public function index(Request $request)
    {
        $query = Task::orderBy('id', 'desc');
        if (isset($request->status)) {
            $query->whereStatus($request->status);
        }
        if (isset($request->text)) {
            $query->where('text', 'like', '%' . $request->text . '%');
        }
        return TaskResource::collection($query->paginate(10));
    }

    public function store(Request $request)
    {
        return Task::create($request->all());
    }

    public function show($id)
    {
        return new Resource(Admin::find($id));
    }

    public function update(Request $request, $id)
    {
        Task::find($id)->update($request->all());
    }

    public function destroy($id)
    {
        Task::find($id)->delete();
    }
}
