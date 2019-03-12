<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Client\Client, Review\Review, Review\ReviewComment, Review\AbstractReview};
use App\Http\Resources\Review\{ReviewResource, ClientReviewCollection, AbstractReviewCollection};
use Illuminate\Database\Eloquent\Builder;
use User;

class ReviewsController extends Controller
{
    protected $filterTablePrefix = [
        'lessons' => ['teacher_id'],
        'groups' => ['year', 'subject_id'],
        'client_lessons' => ['client_id']
    ];

    protected $filters = [
        'equals' => ['client_id'],
        'multiple' => ['teacher_id', 'year', 'subject_id'],
        'rating' => ['client', 'admin', 'final'],
    ];

    public function index(Request $request)
    {
            return $this->adminIndex($request);
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

        if (isset($request->reviewer_admin_id)) {
            Client::find($review->client_id)->update(['reviewer_admin_id' => $request->reviewer_admin_id]);
        }

        return new ReviewResource($review);
    }

    private function adminIndex(Request $request)
    {
        $query = AbstractReview::query();
        $this->filter($request, $query);
        return AbstractReviewCollection::collection(
            $this->showBy($request, $query)
        );
    }

    /**
     * По речи клиентов
     */
    private function clientIndex(Request $request)
    {
        $items = ClientLesson::join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->where('client_lessons.client_id', User::id())
            ->selectRaw('count(*) as lesson_count, client_lessons.grade_id, client_lessons.client_id')
            ->addSelect('lessons.year', 'lessons.subject_id', 'lessons.group_id')
            ->groupBy('lessons.teacher_id', 'lessons.year', 'lessons.subject_id')
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


    protected function filterRating(string $field, $value, Builder &$query)
    {
        $conditions = [];
        foreach(explode(',', $value) as $rating) {
            switch($rating) {
                case -2:
                    $conditions[] = "(
                        reviews.id is null OR
                        not exists(
                            select 1 from review_comments rc
                            where rc.review_id = reviews.id AND rc.type = '{$field}'
                        )
                    )";
                    break;
                default:
                    $conditions[] = "(
                        reviews.id is not null AND
                        exists(
                            select 1 from review_comments rc
                            where
                                rc.review_id = reviews.id AND
                                rc.type = '{$field}' AND
                                rc.rating = {$rating}
                        )
                    )";
            }
        }
        if (count($conditions)) {
            $query->whereRaw("(" . implode(' OR ', $conditions)  .")");
        }
    }
}
