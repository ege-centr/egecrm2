<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Lesson\Lesson, Client\Client, Group\GroupClient};
use App\Http\Resources\Lesson\ScheduleLessonCollection;
use DB;

class ScheduleController extends Controller
{
    public function client($id)
    {
        $group_ids = GroupClient::where('client_id', $id)->pluck('group_id');

        if ($group_ids->count() > 0) {
            $query = Lesson::query()
                ->leftJoin('client_lessons', function($join) use ($id) {
                    $join
                        ->on('client_lessons.lesson_id', '=', 'lessons.id')
                        ->where('client_lessons.client_id', $id);
                })
                ->select(DB::raw('lessons.*, client_lessons.id as client_lesson_id'))
                // проведенные и планируемые занятия
                ->whereRaw("( client_lessons.id is not null or (lessons.status = 'planned' AND lessons.group_id IN ({$group_ids->implode(',')})) )")
                ->orderBy('lessons.date', 'asc')
                ->orderBy('lessons.time', 'asc');
            return ScheduleLessonCollection::collection($query->get());
        }
        return [];
    }
}
