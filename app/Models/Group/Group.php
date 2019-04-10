<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Cabinet, Teacher, Lesson\Lesson, Client\Client, Factory\Subject, Factory\Grade};
use App\Utils\Time;
use App\Traits\Commentable;
use Laravel\Scout\Searchable;
use PersonResource;
use DB;


class Group extends Model
{
    use Commentable, Searchable;

    protected $fillable = [
        'teacher_id', 'head_teacher_id', 'subject_id', 'grade_id', 'teacher_price',
        'year', 'is_archived', 'is_ready_to_start', 'level',
        'latest_start_lesson_id', 'is_contract_signed'
    ];

    public function clients()
    {
        return $this->hasManyThrough(Client::class, GroupClient::class, 'group_id', 'id', 'id', 'client_id');
    }

    public function groupClients()
    {
        return $this->hasMany(GroupClient::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('date', 'asc');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function toSearchableArray()
    {
        $lessons = collect($this->lessons()->notCancelled()->get());
        $schedule = $this->getSchedule();
        return [
            'id' => $this->id,
            'grade_id' => $this->grade_id,
            'subject_id' => $this->subject_id,
            'year' => $this->year,
            'teacher_id' => $this->teacher_id,
            'client_ids' => GroupClient::where('group_id', $this->id)->pluck('client_id'),
            'lessons_count' => $lessons->count(),
            'lessons_conducted_count' => $lessons->where('status', 'conducted')->count(),
            'first_lesson_date' => $lessons->count() > 0 ? $lessons->sortBy('date')->first()->date : null,
            'schedule_label' => $schedule !== null ? $schedule['label'] : null,
        ];
    }

    public function getSchedule()
    {
        if (! $this->year) {
            return null;
        }
        $weekday_intervals = [
            [10, 11],
            [12, 15],
            [16, 17],
            [17, 18],
            [18, 19]
        ];
        $weekend_intervals = [
            [10, 11],
            [12, 13],
            [14, 15],
            [17, 18]
        ];
        $bars = [];
        $labels = [];
        foreach(Time::WEEKDAYS as $weekday => $label) {
            $intervals = $weekday >= 6 ? $weekend_intervals : $weekday_intervals;
            foreach($intervals as $interval) {
                $result = Lesson::join('groups', 'groups.id', '=', 'lessons.group_id')
                    ->selectRaw('time, count(*) as cnt')
                    ->whereRaw("
                        lessons.is_unplanned=0 AND
                        lessons.status <> 'cancelled' AND
                        lessons.group_id={$this->id} AND
                        groups.year={$this->year} AND
                        lessons.time BETWEEN '{$interval[0]}:00:00' AND '{$interval[1]}:00:00' AND
                        DATE_FORMAT(lessons.date, '%w') = " . ($weekday == 7 ? 0 : $weekday))
                    ->first();

                // bars заполняем даже в случае с null
                $bars[$weekday][] = $result->time;
                if ($result->cnt >= 2) {
                    $labels[] = "{$label} в {$result->time}";
                }
            }
        }
        return [
            'bars' => $bars,
            'label' => implode(', ', $labels)
        ];
    }
}
