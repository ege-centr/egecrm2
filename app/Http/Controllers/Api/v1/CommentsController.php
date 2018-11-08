<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Http\Resources\Comment\Resource as CommentResource;

class CommentsController extends Controller
{
    public function index(Request $request)
    {
        return CommentResource::collection(Comment::where([
            'entity_type' => getModelClass($request->class, true),
            'entity_id' => $request->entity_id
        ])->get());
    }

    public function store(Request $request)
    {
        $model = getMorphModel($request->class, $request->entity_id);
        $comment = $model->comments()->create(['text' => $request->text]);
        return new CommentResource($comment);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
