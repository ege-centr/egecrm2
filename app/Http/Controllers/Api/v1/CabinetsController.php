<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Cabinet, Lesson\Lesson};
use App\Utils\Schedule;

class CabinetsController extends Controller
{
    public function index(Request $request)
    {
        return Cabinet::all();
    }

    /**
     * Получить занятые кабинеты в $date $time
     */
    public function occupied(Request $request)
    {
        $query = Lesson::query()
            ->where('date', $request->date)
            ->where('time', $request->time);

        if (isset($request->lesson_id)) {
            $query->where('id', '<>', $request->lesson_id);
        }

        return $query
            ->groupBy('cabinet_id')
            ->pluck('cabinet_id');
    }

    /**
     *
     */
    public function schedule(Request $request)
    {
        return Schedule::cabinet(
            $request->year,
            $request->cabinet_id,
            $request->lesson_id
        );
    }
}
