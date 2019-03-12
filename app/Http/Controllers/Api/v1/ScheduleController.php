<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Client\Client, Group\GroupClient};
use App\Http\Resources\Lesson\ClientLessonCollection;

class ScheduleController extends Controller
{
    public function client($id)
    {
        // Уроки
        $query = ClientLesson::join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->selectRaw('
                client_lessons.*, groups.year, groups.subject_id, lessons.cabinet_id, lessons.group_id,
                lessons.teacher_id, lessons.status, lessons.date, lessons.time, lessons.teacher_id
            ')
            ->where('client_lessons.client_id', $id)
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');
        $group_ids = GroupClient::where('client_id', $id)->pluck('group_id');

        if ($group_ids->count() > 0) {
            $query->leftJoin('lessons as planned_lessons', function($join) use ($group_ids) {
                $join->whereRaw("(planned_lessons.status = 'planned' AND planned_lessons.group_id IN ({$group_ids->implode(',')}))");
            });
            return ClientLessonCollection::collection($query->get());
        }
        return [];
    }
}
