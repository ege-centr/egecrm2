<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\Lesson, Lesson\ClientLesson, Teacher};
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
        foreach($request->clientLessons as $clientLesson) {
            ClientLesson::find($clientLesson['id'])->update($clientLesson);
        }
        return new LessonResource($model);
    }

    public function destroy($id)
    {
        Lesson::find($id)->delete();
    }
}
