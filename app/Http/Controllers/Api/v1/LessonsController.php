<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Journal;
use App\Http\Resources\Journal\Resource as JournalResource;

class LessonsController extends Controller
{
    public function index(Request $request)
    {
        $query = Journal::query();
        if (isset($request->group_id)) {
            $query->whereGroupId($request->group_id);
        }
        return JournalResource::collection($query->get());
    }

    public function store(Request $request)
    {
        return new JournalResource(Journal::create($request->all()));
    }

    public function update(Request $request, $id)
    {
        $model = Journal::find($id);
        $model->update($request->all());
        return new JournalResource($model);
    }

    public function destroy($id)
    {
        Journal::find($id)->delete();
    }
}
