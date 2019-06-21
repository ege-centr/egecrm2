<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Report\{Report, AbstractReport};
use App\Models\{Client\Client, Teacher};
use App\Http\Resources\Report\{AbstractReportCollection, ReportResource, ReportCollection};
use App\Http\Resources\AlgoliaResult;
use App\Http\Resources\Lesson\ClientLessonCollection;
use User;
use App\Http\Requests\Report\StoreOrUpdateRequest;

class ReportsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id', 'is_available_for_parents', 'exists'],
        'multiple' => ['year', 'subject_id', 'teacher_id'],
    ];

    public function index(Request $request)
    {
        $query = AbstractReport::search()->with([
            'facets' => ['*']
        ]);
        $this->filter($request, $query);
        $result = new AlgoliaResult($query->paginateRaw($request->paginate ?: SHOW_ALL));
        $result->getCollection()->transform(function ($items, $key) {
            if ($key === 'hits') {
                foreach($items as &$item) {
                    $item['client'] = new \PersonResource(Client::find($item['client_id']));
                    $item['report'] = $item['report_id'] > 0 ? new ReportCollection(Report::find($item['report_id'])) : null;
                }
            }
            if ($key === 'facets' && count($items) === 0) {
                return null;
            }
            return $items;
        });
        return $result;
    }

    public function show($id)
    {
        return new ReportResource(Report::find($id));
    }

    public function store(StoreOrUpdateRequest $request)
    {
        $item = Report::create($request->all());
        return new ReportResource($item);
    }

    public function update($id, StoreOrUpdateRequest $request)
    {
        $item = Report::find($id);
        if (User::isTeacher() && $item->is_not_moderated) {
            $request->merge(['is_not_moderated' => 0]);
        }
        $item->update($request->all());
        return new ReportResource($item);
    }

    public function destroy($id)
    {
        Report::find($id)->delete();
    }

    public function clientLessons(Request $request)
    {
        return ClientLessonCollection::collection(
            AbstractReport::getClientLessons((object) $request->all())->orderBy('lessons.date')->get()
        );
    }
}
