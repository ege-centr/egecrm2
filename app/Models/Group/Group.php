<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Cabinet, Teacher, Lesson\Lesson, Client\Client, Factory\Subject, Factory\Grade};
use App\Utils\Time;
use App\Traits\Commentable;
use DB;


class Group extends Model
{
    use Commentable;

    protected $fillable = [
        'teacher_id', 'head_teacher_id', 'subject_id', 'grade_id', 'teacher_price',
        'duration', 'year', 'is_archived', 'is_ready_to_start', 'cabinet_id', 'level',
        'latest_start_lesson_id'
    ];

    public function clients()
    {
        return $this->hasManyThrough(Client::class, GroupClient::class, 'group_id', 'id', 'id', 'client_id');
    }

    public function groupClients()
    {
        return $this->hasMany(GroupClient::class);
    }

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class);
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

    public function getSchedule()
    {
        if (! $this->year) {
            return null;
        }
        $weekday_intervals = [
            [10, 11],
            [12, 13],
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
                $time = Lesson::whereRaw("
                    is_unplanned=0 AND
                    group_id={$this->id} AND
                    year={$this->year} AND
                    time BETWEEN '{$interval[0]}:00:00' AND '{$interval[1]}:00:00' AND
                    DATE_FORMAT(date, '%w') = " . ($weekday == 7 ? 0 : $weekday))
                ->value('time');
                $bars[$weekday][] = $time;
                if ($time) {
                    $labels[] = "{$label} в {$time}";
                }
            }
        }
        return [
            'bars' => $bars,
            'label' => implode(', ', $labels)
        ];
    }
}
