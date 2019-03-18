<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\Lesson, Lesson\ClientLesson, Teacher, Client\Client, Group\Group};
use App\Http\Resources\Lesson\{LessonResource, LessonCollection};
use User;

class LessonsController extends Controller
{
    protected $filters = [
        'equals' => ['group_id', 'status', 'teacher_id']
    ];

    public function index(Request $request)
    {
        $query = Lesson::orderBy('date', 'asc')->orderBy('time', 'asc');
        $this->filter($request, $query);
        return LessonCollection::collection($query->get());
    }

    public function store(Request $request)
    {
        return new LessonResource(Lesson::create($request->all()));
    }

    public function show($id)
    {
        return new LessonResource(Lesson::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Lesson::find($id);
        $model->update($request->all());
        foreach($request->clientLessons as $clientLesson) {
            if (isset($clientLesson['id'])) {
                $client_lesson = ClientLesson::find($clientLesson['id']);
                if (isset($clientLesson['to_be_deleted'])) {
                    $client_lesson->delete();
                } else {
                    $client_lesson->update($clientLesson);
                }
            } else {
                ClientLesson::create(array_merge($clientLesson, ['id' => $id]));
            }
        }
        return new LessonResource($model);
    }

    public function destroy($id)
    {
        Lesson::find($id)->delete();
    }

    public function conduct($id, Request $request)
    {
        $lesson = Lesson::find($id);
        $group = Group::find($lesson->group_id);
        $lesson->status = 'conducted';
        $lesson->conducted_at = now()->format(DATE_FORMAT);
        $lesson->conducted_email_id = User::emailId();
        $lesson->price = $group->teacher_price;
        $lesson->save();

        foreach ($request->clients as $client) {
            ClientLesson::create([
                'client_id' => $client['id'],
                'lesson_id' => $id,
                // TODO: take price and grade from contracts
                'grade_id' => Client::whereId($client['id'])->value('grade_id'),
                'price' => 1600,
                'is_absent' => isset($client['is_absent']) && $client['is_absent'] === true,
                'late' => isset($client['late']) ? $client['late'] : null,
                'comment' => isset($client['comment']) ? $client['comment'] : '',
            ]);
        }

        return new LessonResource($lesson->fresh());
    }
}
