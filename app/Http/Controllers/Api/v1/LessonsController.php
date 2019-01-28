<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\Lesson, Lesson\ClientLesson, Teacher, Client\Client};
use App\Http\Resources\Lesson\{Resource as LessonResource, Client as ClientLessonResource};

class LessonsController extends Controller
{
    protected $filters = [
        'equals' => ['group_id']
    ];

    public function index(Request $request)
    {
        $query = Lesson::query();
        $this->filter($request, $query);
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
