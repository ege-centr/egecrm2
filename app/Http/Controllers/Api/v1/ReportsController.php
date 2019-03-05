<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Report\{Report, AbstractReport};
use App\Http\Resources\Report\{AbstractReportCollection, ReportResource};

class ReportsController extends Controller
{
    protected $filterTablePrefix = [
        'lessons' => ['teacher_id', 'entity_id', 'year', 'subject_id'],
        'reports' => ['is_available_for_parents']
    ];

    protected $filters = [
        'equals' => ['teacher_id', 'entity_id', 'is_available_for_parents'],
        'multiple' => ['year', 'subject_id']
    ];

    public function index(Request $request)
    {
        $query = AbstractReport::query();
        $this->filter($request, $query);

        if (isset($request->teacher_ids) && count($request->teacher_ids) > 0) {
            $query->whereIn('lessons.teacher_id', $request->teacher_ids);
        }

        if (isset($request->exists) && $request->exists) {
            $query->whereNotNull('reports.id');
        }

        return AbstractReportCollection::collection(
           $this->showBy($request, $query)
        );
    }

    public function show($id)
    {
        return new ReportResource(Report::find($id));
    }

    public function update($id, Request $request)
    {
        $item = Report::find($id);
        $item->update($request->all());
        return new ReportResource($item);
    }
}
