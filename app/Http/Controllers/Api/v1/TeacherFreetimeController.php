<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Teacher, TeacherFreetime};
use App\Http\Resources\Teacher\FreetimeResource;

class TeacherFreetimeController extends Controller
{
    protected $filters = [
        'equals' => ['teacher_id']
    ];

    public function index(Request $request)
    {
        $query = TeacherFreetime::query();
        $this->filter($request, $query);
        return FreetimeResource::collection(
            $this->showAll($query)
        );
    }

    public function show($id)
    {
        return TeacherFreetime::find($id);
    }

    public function update($id, Request $request)
    {
        TeacherFreetime::find($id)->update($request->all());
    }

    public function store(Request $request)
    {
        $teacherId = $request->teacher_id;
        TeacherFreetime::where('teacher_id', $teacherId)->delete();
        foreach($request->items as $item) {
            TeacherFreetime::create(array_merge($item, ['teacher_id' => $teacherId]));
        }
    }

    public function destroy($id)
    {
        TeacherFreetime::find($id)->delete();
    }
}
