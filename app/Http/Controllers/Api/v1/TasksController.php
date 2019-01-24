<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Resources\Task\{Resource, Collection};

class TasksController extends Controller
{
    protected $filters = [
        'equals' => ['status'],
        'like' => ['text'],
    ];

    public function index(Request $request)
    {
        $query = Task::orderBy('id', 'desc');
        $this->filter($request, $query);
        return Collection::collection($this->showBy($request, $query));
    }

    public function store(Request $request)
    {
        return Task::create($request->all());
    }

    public function show($id)
    {
        return new Resource(Task::find($id));
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
