<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Lesson\Lesson, Client\Client, Group\GroupClient};
use App\Http\Resources\Lesson\ScheduleLessonCollection;
use DB;

class ScheduleController extends Controller
{
    protected $filterTablePrefix = [
        'groups'    => ['year', 'subject_id'],
        'lessons'   => ['teacher_id']
    ];

    protected $filters = [
        'equals' => ['teacher_id', 'subject_id', 'year']
    ];

    public function client($id, Request $request)
    {
        $group_ids = GroupClient::where('client_id', $id)->pluck('group_id');

        // Если ученик не присутствует ни в каких группах, у него нет расписания
        // TODO: хотя должно быть. должно отображаться то, что он уже посетил
        if ($group_ids->count() > 0) {
            $query = Lesson::query()
                ->leftJoin('client_lessons', function($join) use ($id) {
                    $join
                        ->on('client_lessons.lesson_id', '=', 'lessons.id')
                        ->where('client_lessons.client_id', $id);
                })
                ->join('groups', 'groups.id', '=', 'lessons.group_id')
                ->select(DB::raw('lessons.*, client_lessons.id as client_lesson_id'))
                // Отображать расписание нужно:
                // либо с последнего непосещенного
                // либо всё
                ->whereRaw("((lessons.status = 'conducted' AND client_lessons.id IS NOT NULL) OR lessons.status != 'conducted')")
                ->whereIn('lessons.group_id', $group_ids)
                ->orderBy('lessons.date', 'asc')
                ->orderBy('lessons.time', 'asc');

            $this->filter($request, $query);

            return ScheduleLessonCollection::collection($query->get());
        }
        return [];
    }
}
