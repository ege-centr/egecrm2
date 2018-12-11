<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Http\Resources\Lesson\Resource as LessonResource;

class LessonsController extends Controller
{
    public function index(Request $request)
    {
        $query = Lesson::query();
        if (isset($request->group_id)) {
            $query->whereGroupId($request->group_id);
        }
        return LessonResource::collection($query->get());
    }

    public function store(Request $request)
    {
        return new LessonResource(Lesson::create($request->all()));
    }

    public function update(Request $request, $id)
    {
        $model = Lesson::find($id);
        $model->update($request->all());
        return new LessonResource($model);
    }

    public function destroy($id)
    {
        Lesson::find($id)->delete();
    }
}
