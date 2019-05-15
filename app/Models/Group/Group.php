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
            'schedule' => $this->getSchedule(),
        ];
    }

    public function getSchedule()
    {
        $result = Lesson::query()
            ->selectRaw("DATE_FORMAT(lessons.date, '%w') as `weekday`, `time`, `duration`, count(*) as `count`")
            ->where('group_id', $this->id)
            ->groupBy('weekday', 'time', 'duration')
            ->having('count', '>', 1)
            ->get()
            ->all();

        return array_map(function ($item) {
            return [
                'start' => $item->time,
                'end' => (new \DateTime($item->time))->modify("+{$item->duration} minutes")->format("H:i"),
                'weekday' => $item->weekday,
            ];
        }, $result);
    }
}
