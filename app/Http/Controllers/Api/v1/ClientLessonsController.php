<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\Lesson, Lesson\ClientLesson, Teacher, Client\Client};
use App\Http\Resources\Lesson\{LessonResource,  ClientLessonResource};

class ClientLessonsController extends Controller
{

    public function update(Request $request, $id)
    {
        $model = Lesson::find($id);
        $model->update($request->all());
        foreach($request->clientLessons as $clientLesson) {
            ClientLesson::find($clientLesson['id'])->update($clientLesson);
        }
        return new LessonResource($model);
    }

    /**
     * Add client to existing lesson
     */
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required',
            'client_id' => 'required',
        ]);

        $lesson = Lesson::find($request->lesson_id);
        $client_lesson = new ClientLesson($lesson->toArray());
        foreach($lesson->toArray() as $field => $value) {
            $client_lesson->{$field} = $value;
        }
        unset($client_lesson['id']);
        unset($client_lesson['price']);
        $client_lesson->created_at = now()->format('Y-m-d H:i:s');
        $client_lesson->entity_id = $request->client_id;
        $client_lesson->entity_type = Client::class;
        $client_lesson->save();
        return new ClientLessonResource($client_lesson);
    }

    public function destroy($id)
    {
        ClientLesson::find($id)->delete();
    }
}
