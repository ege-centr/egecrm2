<?php

namespace App\Models\Group;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Cabinet, Teacher, Journal, Client\Client};
use App\Utils\Time;
use DB;

class Group extends Model
{
    protected $fillable = [
        'teacher_id', 'head_teacher_id', 'subject_id', 'grade_id', 'teacher_price',
        'duration', 'year', 'is_archived', 'is_ready_to_start', 'cabinet_id', 'level'
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
        return $this->hasMany(Journal::class);
    }

    public function getSchedule()
    {
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
                $lesson_time = Journal::whereRaw("
                    is_unplanned=0 AND
                    group_id={$this->id} AND
                    year={$this->year} AND
                    lesson_time BETWEEN '{$interval[0]}:00:00' AND '{$interval[1]}:00:00' AND
                    DATE_FORMAT(lesson_date, '%w') = " . ($weekday == 7 ? 0 : $weekday))
                    ->value('lesson_time');
                $bars[$weekday][] = $lesson_time;
                if ($lesson_time) {
                    $labels[] = "{$label} в {$lesson_time}";
                }
            }
        }
        return [
            'bars' => $bars,
            'label' => implode(', ', $labels)
        ];
    }
}
