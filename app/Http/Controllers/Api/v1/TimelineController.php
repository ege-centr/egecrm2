<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Schedule;
use App\Models\Lesson\Lesson;
use App\Models\{Cabinet, Group\GroupClient};
use DateTime;

class TimelineController extends Controller
{
    /**
     * Получить расписание кабинета в группе на год
     */
    public function teacher(Request $request)
    {
        $teacherId = $request->current['teacher_id'];
        return [
            'regular'  => Schedule::get([
                'lessons.teacher_id' => $teacherId,
                'year' => $request->year,
            ], $request->group_id),
            'detailed' => $this->getData($request, [
                'lessons.teacher_id' => $teacherId
            ])
        ];
    }

    public function group(Request $request)
    {
        return [
            'regular'  => Schedule::get([
                'group_id' => $request->group_id
            ], $request->group_id),
            'detailed' => $this->getData($request, [
                'lessons.group_id' => $request->group_id
            ])
        ];
    }

    /**
     * Получить расписание по кабинетам в определенный день
     * группировка по кабинетам
     *
     */
    public function cabinet(Request $request)
    {
        $current = (object) $request->current;

        $query = Lesson::notCancelled()
            ->selectRaw("lessons.`date`, `status`, `cabinet_id`,  `time`, `duration`, `group_id`")
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->whereRaw("DATE_FORMAT(lessons.date, '%w') = " . date('w', strtotime($current->date)))
            ->where('groups.year', $request->year)
            ->when(isset($current->id), function ($query) use ($current) {
                return $query->where('lessons.id', '<>', $current->id);
            })
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');

        $data = $query->get();

        $current->is_current = true;
        $current->status = null;
        $data->push($current);
        $data->sortBy(function ($item) {
            return $item->date . ' ' . $item->time;
        });


        $result = [];

        // Группировка
        // cabinet_id =>
        //  week_day =>
        //      item
        //      item
        foreach($data as $item) {
            $date = new DateTime($item->date);
            $result[$item->cabinet_id][$date->format('W')][] = [
                'start' => $item->time,
                'end' => (new DateTime($item->time))->modify("+{$item->duration} minutes")->format("H:i"),
                'is_current' => $item->is_current ?: false,
                'date' => $item->date,
                'status' => $item->status,
            ];
        }

        // Сгруппировать по неделям, начиная с 1 недели сентября
        // По конец мая
        $cabinetIds = Cabinet::pluck('id');
        $current = strtotime("first Monday of September " . $request->year);
        $end = date('W', strtotime("first Monday of June " . ($request->year + 1)));
        $resultGroupedByWeeks = [];
        do {
            $week = date('W', $current);

            foreach($cabinetIds as $cabinetId) {
                $resultGroupedByWeeks[$cabinetId][$week] = isset($result[$cabinetId][$week]) ? array_values($result[$cabinetId][$week]) : [];
            }

            $current = strtotime('next Monday', $current);
        } while ($week !== $end);

        // Номер недели неважен, JS автоматически сортирует ключи
        return array_map(function ($items) {
            return array_values($items);
        }, $resultGroupedByWeeks);
    }

    private function getData(Request $request, $filters = [])
    {
        $current = (object) $request->current;

        $query = Lesson::notCancelled()
            ->selectRaw("
                lessons.`date`, `status`, `time`, `duration`, `group_id`, lessons.`teacher_id`, `cabinet_id`, lessons.`id`,
                (SELECT GROUP_CONCAT(client_id) FROM group_clients WHERE group_id = lessons.group_id) as `client_ids`
            ")
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->where('groups.year', $request->year)
            ->when(isset($current->id), function ($query) use ($current) {
                return $query->where('lessons.id', '<>', $current->id);
            })
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');

        foreach($filters as $field => $value) {
            $query->where($field, $value);
        }

        $data = $query->get();

        if (isset($current->date)) {
            $current->is_current = true;
            $current->status = null;
            $current->client_ids = GroupClient::where('group_id', $request->group_id)->pluck('client_id')->implode(',');
            $data->push($current);
            $data->sortBy(function ($item) {
                return $item->date . ' ' . $item->time;
            });
        }

        // проверить пересечения
        // в одну дату и время не может быть сразу 2 занятия:
        //  * в одном и том же кабинете
        //  * с одним и тем же учеником
        //  * с одним и тем же преподом
        foreach($data as &$item) {
            // logger(json_encode($item, JSON_PRETTY_PRINT));
            $item->start = $item->time;
            $item->end = (new DateTime($item->time))->modify("+{$item->duration} minutes")->format("H:i");
            $item->overlaps = Lesson::query()
                ->where('date', $item->date)
                ->whereRaw(sprintf("
                    (
                        ('%s' BETWEEN `time` AND `time` + INTERVAL `duration` MINUTE) OR
                        ('%s' BETWEEN `time` AND `time` + INTERVAL `duration` MINUTE)
                    )
                    AND
                    (
                        teacher_id = %d OR
                        cabinet_id = %d %s
                    )
                ",
                    $item->start,
                    $item->end,
                    $item->teacher_id,
                    $item->cabinet_id,
                    $item->client_ids ? "OR
                        EXISTS (
                            SELECT 1 FROM group_clients
                            WHERE group_id = lessons.group_id
                            AND client_id IN ({$item->client_ids})
                        )" : ''
                ))
                ->when($item->id, function ($query, $id) {
                    return $query->where('id', '<>', $id);
                })
                ->exists();
        }

        $result = [];
        foreach($data as $item) {
            $date = new DateTime($item->date);
            $result[$date->format('W')][] = [
                'start' => $item->start,
                'end' => $item->end,
                'is_current' => $item->is_current ?: false,
                'date' => $item->date,
                'status' => $item->status,
                'overlaps' => $item->overlaps,
            ];
        }

        $current = strtotime("first Monday of September " . $request->year);
        $end = date('W', strtotime("first Monday of June " . ($request->year + 1)));
        $resultGroupedByWeeks = [];
        do {
            $week = date('W', $current);
            $resultGroupedByWeeks[date('Y-W', $current)] = isset($result[$week]) ? array_values($result[$week]) : [];
            $current = strtotime('next Monday', $current);
        } while ($week !== $end);

        // Номер недели неважен, JS автоматически сортирует ключи
        return array_values(
            array_map(function ($items) {
                return array_values($items);
            }, $resultGroupedByWeeks)
        );
    }
}