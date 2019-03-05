<?php

namespace App\Http\Resources\Report;

use Illuminate\Http\Resources\Json\Resource;
use App\Http\Resources\Person\PersonResource;

class AbstractReportCollection extends Resource
{
    public function toArray($request)
    {
        return extractFields($this, [
            'year', 'subject_id', 'client_id', 'teacher_id', 'lesson_count', 'grade_id',
        ], [
            'report' => new ReportCollection($this->report),
            'teacher' => new PersonResource($this->teacher),
            'client' => new PersonResource($this->client),
        ]);
    }
}
