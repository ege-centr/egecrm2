<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Group\Group,
    Client\Client,
    Lesson
};
use DB, DateTime;
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

        // включить в результаты абстрактные группы
        if (isset($request->abstract)) {
            $abstractGroups = jsonRedecode(app()->call('App\Http\Controllers\Api\v1\AbstractGroupsController@index'))->data;
            $result = jsonRedecode($result);
            $result->data = array_merge($result->data, $abstractGroups);
        }

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $item = Group::create($request->input());
        return new GroupResource($item);
    }

    public function show($id)
    {
        return new GroupResource(Group::find($id));
    }

    public function update(Request $request, $id)
    {
        $item = Group::find($id);
        $item->update($request->all());
        return new GroupResource($item);
    }

    public function destroy($id)
    {
        Group::find($id)->delete();
    }

    public function helper(Request $request)
    {
        $dateMonth = date('-m-d');
        $data = [];
        $result = [];
        foreach(range(academicYear(), academicYear() - 3) as $i => $academicYear) {
            $dateEnd = (date('Y') - $i) . $dateMonth;
            $dateStart = (new DateTime($dateEnd))->modify('-200 days')->format(DATE_FORMAT);
            $data[$academicYear] = DB::select("
                select y.min_date as `date`, sum(y.cnt) as `sum` from (
                    select min_date, count(*) as cnt from (
                        select min(c.date) as min_date from contract_subjects cs
                        join contracts c on c.id = cs.contract_id
                        where
                            cs.subject_id={$request->subject_id} and
                            c.grade_id={$request->grade_id} and
                            c.year={$academicYear}
                        group by c.number
                    ) as x
                    group by min_date
                ) as y
                where y.min_date between '{$dateStart}' and '{$dateEnd}'
                group by `date`
            ");

            // $date = new DateTime($dateEnd);
            // foreach(range(1, 90) as $i) {
            //     $dateFormatted = $date->format(DATE_FORMAT);
            //     $result[$academicYear][$dateFormatted] = collect($data[$academicYear])->where('date', $dateFormatted);
            //     $date->modify('-1 day');
            // }
        }

        return $data;
    }
}
