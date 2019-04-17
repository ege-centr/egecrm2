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
     * @param array $filters фильтры в формате [field => value]
     * @param int|null текущая группа, отображается вытяннутым элементом на timeline
     */
    public static function get(array $filters, int $currentGroupId = null) : array
    {
        $query = Lesson::query()
            ->selectRaw("DATE_FORMAT(lessons.date, '%w') as `weekday`, `date`, `time`, `duration`, `group_id`, count(*) as `count`")
            ->groupBy('group_id', 'weekday', 'time', 'duration')
            ->having('count', '>', 1);

        // если необходимо срезать по году, то подключаем группу
        if (isset($filters['year'])) {
            $query->join('groups', 'groups.id', '=', 'lessons.group_id');
        }

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
