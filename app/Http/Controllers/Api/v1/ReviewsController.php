<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Client\Client, Review\Review, Review\ReviewComment};
use App\Http\Resources\Review\{ReviewResource, ClientReviewCollection};
use User;

class ReviewsController extends Controller
{
    public function index(Request $request)
    {
            return $this->{strtolower(class_basename($_SESSION['user']['class'])) . 'Index'}($request);
    }

    public function store(Request $request)
    {
        $review = Review::create($request->all());
        foreach($request->comments as $comment) {
            $review->comments()->create($comment);
        }
        return new ReviewResource($review->fresh());
    }

    public function show($id)
    {
        return new ReviewResource(Review::find($id));
    }

    public function update($id, Request $request)
    {
        $review = Review::find($id);
        $review->update($request->all());
        foreach($request->comments as $comment) {
            if (isset($comment['id'])) {
                ReviewComment::find($comment['id'])->update($comment);
            } else {
                $review->comments()->create($comment);
            }
        }
        return new ReviewResource($review);
    }

    private function adminIndex(Request $request)
    {
        $query = Review::orderBy('id', 'desc');
        return ReviewResource::collection(
            $this->showBy($request, $query)
        );
    }

    /**
     * По речи клиентов
     */
    private function clientIndex(Request $request)
    {
        $items = ClientLesson::where('entity_type', Client::class)
            ->where('entity_id', User::id())
            ->selectRaw('count(*) as lesson_count, client_grade_id as grade_id, entity_id as client_id')
            ->addSelect('year', 'subject_id', 'group_id', 'client_grade_id')
            ->groupBy('teacher_id', 'year', 'subject_id')
            ->get();

        foreach($items as $item) {
            $item->review = new ReviewResource(Review::where([
                ['year', $item->year],
                ['client_id', $item->client_id],
                ['teacher_id', $item->teacher_id],
                ['grade_id', $item->grade_id],
                ['subject_id', $item->subject_id]
            ])->first());

        }

        return imitatePagination(
            ClientReviewCollection::collection($items)
        );
    }
}
