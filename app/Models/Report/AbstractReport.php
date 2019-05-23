<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;
use App\Models\{ Teacher, Client\Client, Lesson\ClientLesson };
use Illuminate\Database\Eloquent\Builder;
use Laravel\Scout\Searchable;

/**
 * Абстрактный, возможно ещё не созданный отчет
 */
class AbstractReport extends Model
{
    use Searchable;

    protected $primaryKey = 'reports.id';

    protected $table = 'client_lessons';

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

    public function searchableAs()
    {
        return 'abstract_reports';
    }

    public function getScoutKey()
    {
        return $this->report_id ?? implode('-', [
            $this->client_id,
            $this->teacher_id,
            $this->subject_id,
            $this->year,
        ]);
    }

    /**
     * Для созданных отчётов = спустя какое кол-во занятий был создан отчёт?
     * Для абстрактных = сколько занятий прошло с момента последнего отчета?
     */
    public static function getClientLessons($abstractReport)
    {
        $query = ClientLesson::join('lessons', 'lessons.id', '=', 'client_lessons.lesson_id')
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->where('client_lessons.client_id', $abstractReport->client_id)
            ->where('lessons.teacher_id', $abstractReport->teacher_id)
            ->where('groups.subject_id', $abstractReport->subject_id)
            ->where('groups.year', $abstractReport->year);

        if ($abstractReport->report_id === null) {
            return $query->where('lessons.date', '>=', $abstractReport->lesson_date);
        }
        return $query
            ->where('lessons.date', '<=', $abstractReport->report_date)
            ->whereRaw("lessons.date > IFNULL(
                (
                    select max(reports.date) from reports
                    where
                        reports.client_id = client_lessons.client_id and
                        reports.teacher_id = lessons.teacher_id and
                        reports.subject_id = groups.subject_id and
                        reports.year = groups.year and
                        reports.date < '{$abstractReport->report_date}'
                ), '0000-00-00')
            ");
    }

    public function toSearchableArray()
    {
        return array_merge($this->toArray(), [
            'lesson_count' => self::getClientLessons($this)->count(),
            'exists' => intval($this->report_id > 0),
        ]);
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
                    client_lessons.grade_id, client_lessons.client_id, reports.is_available_for_parents,
                    min(lessons.date) as lesson_date, reports.date as report_date
                ')
                ->groupBy('reports.id', 'client_lessons.client_id', 'lessons.teacher_id', 'groups.subject_id', 'groups.year');
        });
    }
}
