<?php

namespace App\Models\Lesson;

use Illuminate\Database\Eloquent\Model;
use App\Models\{Group\Group, Cabinet, Teacher, Admin\Admin, Email};
use App\Traits\HasCreatedEmail;
use App\Models\Factory\Grade;

class Lesson extends Model
{
    use HasCreatedEmail;

    /**
     * Статусы занятий
     */
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_CONDUCTED = 'conducted';
    const STATUS_PLANNED = 'planned';

    protected $touches = ['group'];

    protected $fillable = [
        'teacher_id', 'cabinet_id', 'date', 'time', 'price', 'duration',
        'status', 'is_unplanned', 'group_id', 'bonus', 'topic'
    ];

    public function cabinet()
    {
        return $this->belongsTo(Cabinet::class, 'cabinet_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function clientLessons()
    {
        return $this->hasMany(ClientLesson::class);
    }

    public function getTimeAttribute()
    {
        if ($this->attributes['time']) {
            return mb_strimwidth($this->attributes['time'], 0, 5);
        }
    }

    public function getConductedUserAttribute()
    {
        return Email::getUser($this->conducted_email_id);
    }

    /**
     * первое занятие в группе?
     */
    public function getIsFirstInGroupAttribute()
    {
        return $this->date === self::where('group_id', $this->group_id)
            ->where('status', '<>', LessonStatus::CANCELLED)
            ->min('date');
    }

    /**
     * занятие не зарегистрировано?
     */
    public function getIsNotRegisteredAttribute()
    {
        return $this->date < now()->format(DATE_FORMAT)
            && $this->status !== LessonStatus::CANCELLED
            && $this->status !== LessonStatus::CONDUCTED;
    }

    public function getClientsCountAttribute()
    {
        if ($this->is_conducted) {
            return $this->clientLessons()->count();
        }
        return $this->group->groupClients()->count();
    }

    public function scopeNotCancelled($query)
    {
        return $query->where('status', '<>', LessonStatus::CANCELLED);
    }

    /**
     * Высчитать бонус
     *
     * https://lk.ege-centr.ru/tasks?id=2563
     */
    public function calculateBonus()
    {
        // для экстерната бонус равен нулю
        if ($this->group->grade_id == Grade::EXTERNAL) {
            return 0;
        }

        $query = self::query()
            ->where('group_id', $this->group_id)
            ->where('teacher_id', $this->teacher_id)
            ->whereRaw(sprintf("CONCAT(lessons.date, ' ', lessons.time) <= '%s %s:00'", $this->date, $this->time))
            ->where('status', 'conducted');

        $maxClientsCount = (clone $query)
            ->leftJoin('client_lessons', 'client_lessons.lesson_id', '=', 'lessons.id')
            ->groupBy('client_id')
            ->get()
            ->count();

        // таких ситуаций в идеале быть не должно
        // division by zero
        if ($maxClientsCount === 0) {
            return 0;
        }

        // номер занятия по порядку
        $lessonNumber = (clone $query)->count();

        /**
         * k - коэффициент, зависящий от max_students_count.
         * Если max_students_count менее 5, то k=0. Если 6, то 0,6.
         * Если 7, то 0,7. Если 8, то 0,8. Если 9, то 0,9, если 10 и более, то 1
         */
        $k = $maxClientsCount < 5 ? 0 : ($maxClientsCount >= 10 ? 1 : ($maxClientsCount / 10));

        /**
         * b - база в рублях. b = 80 + step1 * lesson_count.
         * То есть для 1 занятия это 100. Для 2 занятия это 120 и т.д. (step фиксирован и равен 20)
         */
        $b = 80 + (20 * $lessonNumber);

        /**
         * s - приведенная доля полноты группы.
         * s =  (p - r) / (1 - r)
         * p - доля полноты группы, то есть p = students_count / max_students_count
         * r - норматив доли (минимальная доля). Расчитывается как 0,87 - step2 * lesson_count.
         * То есть для 1 занятия 0,86. Для 2-го 0,85 и т.д. (step2 фиксирован и равен 0,01)
         */
        $p = $this->clientLessons()->count() / $maxClientsCount;
        $r = 0.87 - (0.01 * $lessonNumber);
        $s = ($p - $r) / (1 - $r);

        $bonus = $k * $b * $s;

        // logger(sprintf("lesson_id: %s \t lesson_number: %s \t max_clients_count: %s | clients_count: %s | bonus: %s",
		// 	$this->id,
		// 	$lessonNumber,
		// 	$maxClientsCount,
		// 	$this->clientLessons()->count(),
		// 	$bonus
		// ));

        /**
         * если бонус меньше 0, то на самом деле бонус просто не начисляется
         * То есть в данном случае он просто = 0
         */
        return $bonus < 0 ? 0 : $bonus;
    }
}
