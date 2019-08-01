<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Resources\Task\{Resource, Collection};
use User;

class TasksController extends Controller
{
    protected $filters = [
        'multiple' => ['status', 'responsible_admin_id', 'created_email_id'],
        'like' => ['text'],
    ];

    public function index(Request $request)
    {
        $query = Task::orderBy('id', 'desc');
        $this->filter($request, $query);
        $query->whereRaw(sprintf(
            "(created_email_id = %s OR responsible_admin_id = %s)",
            User::emailId(),
            User::id()
        ));
        return Collection::collection($this->showBy($request, $query));
    }

    public function store(Request $request)
    {
        $item = Task::create($request->all());
        foreach($request->input('files') as $file) {
            $item->files()->create($file);
        }
        $item->save();
        return $item;
    }

    public function show($id)
    {
        return new Resource(Task::find($id));
    }

    public function update(Request $request, $id)
    {
        $item = Task::find($id);
        $item->files()->delete();
        foreach($request->input('files') as $file) {
            $item->files()->create($file);
        }
        $item->update($request->all());
        return $item;
    }

    public function destroy($id)
    {
        Task::find($id)->delete();
    }
}
