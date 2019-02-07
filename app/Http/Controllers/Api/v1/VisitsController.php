<?php

namespace App\Http\Controllers\Api\v1;

use DateTime;
use DateInterval;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lesson\Lesson;
use App\Http\Resources\Lesson\LessonCollection;

class VisitsController extends Controller
{
    public function index(Request $request)
    {
        $date = new DateTime($request->date);
        $date->sub(new DateInterval('P' . (($request->page - 1) * $request->paginate) . 'D'));
        $return = [];
        foreach(range(1, $request->paginate) as $i) {
            $date_formatted = $date->format('Y-m-d');
            $return[$date_formatted] = LessonCollection::collection(Lesson::where('date', $date_formatted)->get());
            $date->sub(new DateInterval('P1D'));
        }

        $data = [];
        foreach($return as $date => $items) {
            $data[] = compact('date', 'items');
        }

        return [
            'data' => $data,
            'meta' => [
                'current_page' => intval($request->page),
                'last_page' => 999,
            ]
        ];
    }
}
