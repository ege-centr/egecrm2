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
    protected $table = 'client_lessons';

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
        $query = ClientLesson::join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->where('client_lessons.client_id', $this->client_id)
            ->where('lessons.teacher_id', $this->teacher_id)
            ->where('groups.subject_id', $this->subject_id)
            ->where('groups.year', $this->year);

        if ($this->report_id === null) {
            return $query->where('lessons.date', '>=', $this->lesson_date)->count();
        }
        return $query
            ->where('lessons.date', '<=', $this->report_date)
            ->whereRaw("lessons.date > IFNULL(
                (
                    select max(reports.date) from reports
                    where
                        reports.client_id = client_lessons.client_id and
                        reports.teacher_id = lessons.teacher_id and
                        reports.subject_id = groups.subject_id and
                        reports.year = groups.year and
                        reports.date < '{$this->report_date}'
                ), '0000-00-00')
            ")
            ->count();
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('abstract-report', function($query) {
            return $query->join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
                ->join('groups', 'groups.id', '=', 'lessons.group_id')
                ->leftJoin('reports', function($join) {
                    $join->on('reports.client_id', '=', 'client_lessons.client_id')
                        ->on('reports.teacher_id', '=', 'lessons.teacher_id')
                        ->on('reports.subject_id', '=', 'groups.subject_id')
                        ->on('reports.year', '=', 'groups.year')
                        ->on('reports.date', '>=', 'lessons.date');
                })
                ->selectRaw('
                    reports.id as report_id, groups.year, groups.subject_id, lessons.teacher_id,
                    client_lessons.grade_id, client_lessons.client_id,
                    min(lessons.date) as lesson_date, reports.date as report_date
                ')
                ->groupBy('reports.id', 'client_lessons.client_id', 'lessons.teacher_id', 'groups.subject_id', 'groups.year');
        });
    }
}