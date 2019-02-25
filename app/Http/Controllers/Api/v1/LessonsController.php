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
        'equals' => ['group_id', 'status', 'entity_id']
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
                ClientLesson::create(array_merge($model->toArray(), $clientLesson));
            }
        }
        return new LessonResource($model);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        $lesson->clientLessons()->delete();
        $lesson->delete();
    }

    public function conduct($id, Request $request)
    {
        $lesson = Lesson::find($id);
        $group = Group::find($lesson->group_id);
        $lesson->status = 'conducted';
        $lesson->entity_type = Teacher::class;
        $lesson->entity_id = $group->teacher_id;
        $lesson->subject_id = $group->subject_id;
        $lesson->group_grade_id = $group->grade_id;
        $lesson->conducted_at = now()->format(DATE_FORMAT);
        $lesson->conducted_email_id = get_class(User::fromSession()) === Teacher::class ? 69 : User::id();
        $lesson->entry_id = uniqid();
        $lesson->duration = 135;
        $lesson->save();

        foreach ($request->clients as $client) {
            ClientLesson::create([
                'status' => 'conducted',
                'entity_id' => $client['id'],
                'conducted_at' => now()->format(DATE_FORMAT),
                'conducted_email_id' => get_class(User::fromSession()) === Teacher::class ? 69 : User::id(),
                'group_grade_id' => $group->grade_id,
                'client_grade_id' => Client::whereId($client['id'])->value('grade_id'),
                'entry_id' => $lesson->entry_id,
                'subject_id' => $lesson->subject_id,
                'is_absent' => isset($client['is_absent']) && $client['is_absent'] === true,
                'late' => isset($client['late']) ? $client['late'] : null,
                'comment' => isset($client['comment']) ? $client['comment'] : '',
                'group_id' => $lesson->group_id,
                'date' => $lesson->date,
                'time' => $lesson->time,
                'is_unplanned' => $lesson->is_unplanned,
                'year' => $lesson->year,
                'teacher_id' => $lesson->teacher_id,
                'cabinet_id' => $lesson->cabinet_id,
                'duration' => 135,
            ]);
        }

        return new LessonResource($lesson->fresh());
    }
}
