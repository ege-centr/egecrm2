<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Report\{Report, AbstractReport};
use App\Http\Resources\Report\{AbstractReportCollection, ReportResource};

class ReportsController extends Controller
{
    protected $filterTablePrefix = [
        'client_lessons' => ['client_id'],
        'lessons' => ['teacher_id'],
        'groups' => ['year', 'subject_id'],
        'reports' => ['is_available_for_parents']
    ];

    protected $filters = [
        'equals' => ['client_id', 'is_available_for_parents'],
        'multiple' => ['year', 'subject_id', 'teacher_id'],
        'exists' => ['exists']
    ];

    public function index(Request $request)
    {
        $query = AbstractReport::query();
        $this->filter($request, $query);
        return AbstractReportCollection::collection(
           $this->showBy($request, $query)
        );
    }

    public function show($id)
    {
        return new ReportResource(Report::find($id));
    }

    public function store(Request $request)
    {
        $item = Report::create($request->all());
        return new ReportResource($item);
    }

    public function update($id, Request $request)
    {
        $item = Report::find($id);
        $item->update($request->all());
        return new ReportResource($item);
    }

    public function destroy($id)
    {
        Report::find($id)->delete();
    }

    protected function filterExists(string $field, $value, Builder &$query)
    {
        if (intval($value) === 0) {
            $query->whereNull('reports.id');
        } else {
            $query->whereNotNull('reports.id');
        }
    }
}
