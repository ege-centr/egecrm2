<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Teacher, Client\Client, Lesson\ClientLesson };
use Illuminate\Database\Eloquent\Builder;

/**
 * Абстрактный, возможно ещё не созданный отчет
 */
class AbstractReport extends Model
{
    protected $table = 'lessons';

    protected $with = ['report'];

    // кол-во занятий до того, как потребуется отчет
    // (с этой цифры включительно уже требуется)
    const REPORT_NEEDED_LESSON_COUNT = 6;

    public function report()
    {
        return $this->belongsTo(Report::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Для созданных отчётов = спустя какое кол-во занятий был создан отчёт?
     * Для абстрактных = сколько занятий прошло с момента последнего отчета?
     */
    public function getLessonCountAttribute()
    {
        $query = ClientLesson::where('lessons.entity_id', $this->client_id)
            ->where('lessons.teacher_id', $this->teacher_id)
            ->where('lessons.subject_id', $this->subject_id)
            ->where('lessons.year', $this->year);

        if ($this->report_id === null) {
            return $query->where('lessons.date', '>=', $this->lesson_date)->count();
        }
        return $query
            ->where('lessons.date', '<=', $this->report_date)
            ->whereRaw("lessons.date > IFNULL(
                (
                    select max(reports.date) from reports
                    where
                        reports.client_id = lessons.entity_id and
                        reports.teacher_id = lessons.teacher_id and
                        reports.subject_id = lessons.subject_id and
                        reports.year = lessons.year and
                        reports.date < '{$this->report_date}'
                ), '0000-00-00')
            ")
            ->count();
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('abstract-report', function($query) {
            return $query->where('entity_type', Client::class)
                ->leftJoin('reports', function($join) {
                    $join->on('reports.client_id', '=', 'lessons.entity_id')
                        ->on('reports.teacher_id', '=', 'lessons.teacher_id')
                        ->on('reports.subject_id', '=', 'lessons.subject_id')
                        ->on('reports.year', '=', 'lessons.year')
                        ->on('reports.date', '>=', 'lessons.date');
                })
                ->selectRaw('
                    reports.id as report_id, lessons.year, lessons.subject_id, lessons.teacher_id,
                    lessons.client_grade_id as grade_id, lessons.entity_id as client_id,
                    min(lessons.date) as lesson_date, reports.date as report_date
                ')
                ->groupBy('reports.id', 'lessons.entity_id', 'lessons.teacher_id', 'lessons.subject_id', 'lessons.year');
        });
    }
}
