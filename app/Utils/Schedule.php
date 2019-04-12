<?php

namespace App\Utils;

use App\Models\Lesson\Lesson;
use DateTime;

/**
 * Schedule timeline
 */
class Schedule
{
    /**
     * @param array|int $groupIds ID групп, для которых получаем расписание
     * @param int|null текущая группа, отображается вытяннутым элементом на timeline
     */
    public static function get($groupIds, int $currentGroupId = null) : array
    {
        $query = Lesson::query()
            ->selectRaw("DATE_FORMAT(lessons.date, '%w') as `weekday`, `time`, `duration`, `group_id`, count(*) as `count`")
            ->groupBy('weekday', 'time', 'duration')
            ->having('count', '>', 1);

        if (is_array($groupIds)) {
            $query->whereIn('group_id', $groupIds);
        } else {
            $query->where('group_id', $groupIds);
        }

        return array_map(function ($item) use ($currentGroupId) {
            return [
                'is_current' => $item->group_id === $currentGroupId,
                'start' => $item->time,
                'end' => (new DateTime($item->time))->modify("+{$item->duration} minutes")->format("H:i"),
                'weekday' => $item->weekday,
            ];
        }, $query->get()->all());
    }

    /**
     * Получить расписание кабинета на год
     *
     * @param int $year год
     * @param int $cabinetId ID кабинета
     * @param int|null $lessonId ID текущего занятия, чтобы не учитывать
     */
    public static function cabinet(
        int $year,
        int $cabinetId,
        int $lessonId = null
    ) : array
    {
        $query = Lesson::query()
            ->selectRaw("DATE_FORMAT(lessons.date, '%w') as `weekday`, lessons.`date`, `time`, `duration`, `group_id`")
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->where('lessons.cabinet_id', $cabinetId)
            ->where('groups.year', $year)
            ->when($lessonId !== null, function ($query) use ($lessonId) {
                return $query->where('lessons.id', '<>', $lessonId);
            })
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');

        $data = $query->get();

        // group by weeks
        $result = [];
        foreach($data as $item) {
            $date = new DateTime($item->date);
            $result[$date->format('W')][] = [
                'weekday' => $item->weekday,
                'start' => $item->time,
                'end' => (new DateTime($item->time))->modify("+{$item->duration} minutes")->format("H:i"),
                'is_current' => false,
                'date' => $item->date,
            ];
        }

        return array_values($result);
    }
}
