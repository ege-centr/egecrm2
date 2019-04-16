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
     * Получить регулярное расписание
     *
     * @param array|int $groupIds ID групп, для которых получаем расписание
     * @param int|null текущая группа, отображается вытяннутым элементом на timeline
     */
    public static function get(array $filters, int $currentGroupId = null) : array
    {
        $query = Lesson::query()
            ->selectRaw("DATE_FORMAT(lessons.date, '%w') as `weekday`, `date`, `time`, `duration`, `group_id`, count(*) as `count`")
            ->groupBy('weekday', 'time', 'duration')
            ->having('count', '>', 1);

        foreach($filters as $field => $value) {
            if (is_array($value)) {
                $query->whereIn($field, $value);
            } else {
                $query->where($field, $value);
            }
        }

        return array_map(function ($item) use ($currentGroupId) {
            return [
                'is_current' => $item->group_id === $currentGroupId,
                'start' => $item->time,
                'end' => (new DateTime($item->time))->modify("+{$item->duration} minutes")->format("H:i"),
                'date' => $item->date,
            ];
        }, $query->get()->all());
    }
}
