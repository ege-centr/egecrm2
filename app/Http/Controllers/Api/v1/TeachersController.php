<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Http\Resources\Teacher\{Collection as TeacherCollection, Resource as TeacherResource};

class TeachersController extends Controller
{
    protected $filters = [
        'multiple' => ['in_egecentr'],
        'findInSet' => ['subjects_ec']
    ];

    public function index(Request $request)
    {
        $query = Teacher::query();
        $this->filter($request, $query);
        return TeacherCollection::collection(
            $this->showBy($request, $query)
        );
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        return new TeacherResource(Teacher::find($id));
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
