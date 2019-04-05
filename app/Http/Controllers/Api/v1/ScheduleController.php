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

    /**
     * фактически проведенные занятия
     * занятия прошедшие без ученика в группе, в которой ученик сейчас присутствует
     * отмененные занятия в группе, в которой ученик сейчас присутствует
     * планируемые занятия в группе, в которой ученик сейчас присутствует
     */
    public function client($id, Request $request)
    {
        $current_group_ids = GroupClient::where('client_id', $id)->pluck('group_id')->all();
        $visited_group_ids = ClientLesson::query()
            ->join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->where('client_id', $id)
            ->pluck('group_id')
            ->all();

        $group_ids = array_unique(array_merge($current_group_ids, $visited_group_ids));

        // Если ученик не посещал занятий и не присутствует в группах, у него нет расписания
        if (count($group_ids) > 0) {
            $query = Lesson::query()
                ->leftJoin('client_lessons', function($join) use ($id) {
                    $join
                        ->on('client_lessons.lesson_id', '=', 'lessons.id')
                        ->where('client_lessons.client_id', $id);
                })
                ->join('groups', 'groups.id', '=', 'lessons.group_id')
                ->select(DB::raw('lessons.*, client_lessons.id as client_lesson_id'))
                // если в текущей группе, то отображать все занятиия
                // если в группе был когда-то, но уже нет, то только посещённые
                ->whereRaw(sprintf("(lessons.group_id IN (%s) OR (lessons.status = 'conducted' AND client_lessons.id IS NOT NULL))", implode(',', $current_group_ids)))
                ->whereIn('lessons.group_id', $group_ids)
                ->orderBy('lessons.date', 'asc')
                ->orderBy('lessons.time', 'asc');

            $this->filter($request, $query);

            return ScheduleLessonCollection::collection($query->get());
        }
        return [];
    }
}
