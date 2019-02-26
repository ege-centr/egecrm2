<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Client\Client};
use App\Http\Resources\Review\ClientReviewCollection;
use User;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
        if (User::isClient()) {
            return $this->clientIndex($request);
        }
    }

    private function clientIndex(Request $request)
    {
        $query = ClientLesson::where('entity_type', Client::class)
            ->where('entity_id', User::id())
            ->selectRaw('count(*) as lesson_count, client_grade_id as grade_id')
            ->addSelect('year', 'subject_id', 'group_id', 'client_grade_id')
            ->groupBy('teacher_id', 'year', 'subject_id');
        return ClientReviewCollection::collection(
            $this->showAll($query)
        );
    }
}
