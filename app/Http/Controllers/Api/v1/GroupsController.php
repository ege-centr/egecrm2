<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Group\Group,
    Client\Client,
    Lesson
};
use DB;
use App\Http\Resources\Group\{GroupCollection, GroupResource};
use App\Http\Resources\AlgoliaResult;

class GroupsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'teacher_id', 'subject_id', 'grade_id'],
        'equals' => ['client_ids'],
    ];

    public function index(Request $request)
    {
        $query = Group::search()->with([
            'facets' => ['*']
        ]);
        $this->filter($request, $query);
        $result = new AlgoliaResult($query->paginateRaw($request->paginate ?: SHOW_ALL));

        // если передан ID ученика, подсвечиваем группы цветами в зависимости от ДОговора
        if ($request->client_id > 0) {
            $client = Client::find($request->client_id);
            $result->getCollection()->transform(function ($items, $key) use ($client) {
                if ($key === 'hits') {
                    foreach($items as &$item) {
                        $item['subject_status'] = $client->getSubjectStatus($item['year'], $item['subject_id']);
                    }
                }
                return $items;
            });
        }

        return $result;
    }

    public function store(Request $request)
    {
        return new GroupResource(
            Group::create($request->input())
        );
    }

    public function show($id)
    {
        return new GroupResource(Group::find($id));
    }

    public function update(Request $request, $id)
    {
        $model = Group::find($id);
        $model->update($request->all());

        return new GroupResource($model);
    }

    public function destroy($id)
    {
        //
    }
}
