<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Resources\Student\{Resource, Collection};

class StudentsController extends Controller
{
    public function index()
    {
        return resourceCollection(Student::orderBy('id', 'desc')->paginate(30), Collection::class);
    }

    public function store(Request $request)
    {
        $new_model = Student::create($request->input());
        $new_model->phones()->createMany($request->phones);
        $new_model->passport()->create($request->passport);

        $representative = $new_model->representative()->create($request->representative);
        $representative->passport()->create($request->representative['passport']);

        return response($new_model, 201);
    }

    public function show($id)
    {
        return new Resource(Student::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Student::find($id);
        $model->update($request->input());

        $model->phones()->delete();
        $model->phones()->createMany($request->phones);

        return response()->json(null, 204);
    }

    public function destroy($id)
    {
        //
    }
}
