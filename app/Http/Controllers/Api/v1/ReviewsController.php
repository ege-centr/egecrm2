<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Client\Client, Review\Review, Review\ReviewComment, Review\AbstractReview};
use App\Http\Resources\Review\{ReviewResource, ReviewCollection, ClientReviewCollection, AbstractReviewCollection};
use Illuminate\Database\Eloquent\Builder;
use User;
use App\Http\Resources\AlgoliaResult;

class ReviewsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id'],
        'multiple' => ['teacher_id', 'year', 'subject_id', 'client_rating', 'admin_rating', 'final_rating'],
    ];

    public function index(Request $request)
    {
        $query = AbstractReview::search()->with([
            'facets' => ['*']
        ]);
        $this->filter($request, $query);
        $result = new AlgoliaResult($query->paginateRaw($request->paginate ?: SHOW_ALL));
        $result->getCollection()->transform(function ($items, $key) {
            if ($key === 'hits') {
                foreach($items as &$item) {
                    $item['client'] = new \PersonResource(Client::find($item['client_id']));
                    $item['review'] = $item['review_id'] > 0 ? new ReviewCollection(Review::find($item['review_id'])) : null;
                }
            }
            return $items;
        });
        return $result;
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

    public function destroy($id)
    {
        Review::find($id)->delete();
    }
}
