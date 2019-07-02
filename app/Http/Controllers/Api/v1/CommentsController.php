<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Resources\Comment\Resource as CommentResource;
use App\Http\Requests\Comment\StoreOrUpdateRequest;

class CommentsController extends Controller
{
    public function index(Request $request)
    {
        return CommentResource::collection(Comment::where([
            'entity_type' => getModelClass($request->class, true),
            'entity_id' => $request->entity_id
        ])->orderBy('created_at', 'desc')->get());
    }

    public function store(StoreOrUpdateRequest $request)
    {
        $comment = Comment::create([
           'text' => $request->text,
           'entity_type' => getModelClass($request->class, true),
           'entity_id' => $request->entity_id,
        ]);
        return new CommentResource($comment);
    }

    public function show($id)
    {
        //
    }

    public function update(StoreOrUpdateRequest $request, $id)
    {
        $model = Comment::find($id);
        $model->update($request->all());
        return new CommentResource($model);
    }

    public function destroy($id)
    {
        Comment::find($id)->delete();
    }
}
