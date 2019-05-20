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
        $query = Lesson::notCancelled()
            ->selectRaw("
                lessons.`date`, `status`, `time`, `duration`, `group_id`, lessons.`teacher_id`, `cabinet_id`, lessons.`id`,
                (SELECT GROUP_CONCAT(client_id) FROM group_clients WHERE group_id = lessons.group_id) as `client_ids`
            ")
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->whereRaw("DATE_FORMAT(lessons.date, '%w') = " . date('w', strtotime($request->current['date'])))
            ->where('groups.year', $request->year)
            ->when(isset($request->current['id']), function ($query) use ($request) {
                return $query->where('lessons.id', '<>', $request->current['id']);
            })
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');

        $result = $this->getResult($query, $request, true);

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
        // TODO: тут и в cabinets() почти одно и то же, с небольшой разницей
        $query = Lesson::notCancelled()
            ->selectRaw("
                lessons.`date`, `status`, `time`, `duration`, `group_id`, lessons.`teacher_id`, `cabinet_id`, lessons.`id`,
                (SELECT GROUP_CONCAT(client_id) FROM group_clients WHERE group_id = lessons.group_id) as `client_ids`
            ")
            ->join('groups', 'groups.id', '=', 'lessons.group_id')
            ->where('groups.year', $request->year)
            ->when(isset($request->current['id']), function ($query) use ($request) {
                return $query->where('lessons.id', '<>', $request->current['id']);
            })
            ->orderBy('lessons.date', 'asc')
            ->orderBy('lessons.time', 'asc');

        foreach($filters as $field => $value) {
            $query->where($field, $value);
        }

        $result = $this->getResult($query, $request);

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

    /**
     * Проверить каждый зуб на пересечения
     *
     *  В одну дату и время не может быть сразу 2 занятия:
     *  – в одном и том же кабинете
     *  – с одним и тем же учеником
     *  – с одним и тем же преподом
     */
    private function getResult($query, $request, bool $groupByCabinet = false)
    {
        $current = (object) $request->current;

        $items = $query->get();

        if (isset($current->date)) {
            $current->is_current = true;
            $current->status = null;
            $current->client_ids = GroupClient::where('group_id', $request->group_id)->pluck('client_id')->implode(',');
            $items->push($current);
            $items->sortBy(function ($item) {
                return $item->date . ' ' . $item->time;
            });
        }

        foreach($items as &$item) {
            // logger(json_encode($item, JSON_PRETTY_PRINT));
            $item->start = $item->time;
            $item->end = (new DateTime($item->time))->modify("+{$item->duration} minutes")->format("H:i");
            $item->overlaps = Lesson::query()
                ->where('date', $item->date)
                ->whereRaw(sprintf("
                    (
                        '%s' <= CAST(ADDTIME(`time`, `duration` * 100) AS CHAR) AND
                        '%s' >= CAST(`time` AS CHAR)
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
        foreach($items as $item) {
            $date = new DateTime($item->date);
            $data = [
                'start' => $item->start,
                'end' => $item->end,
                'is_current' => $item->is_current ?: false,
                'date' => $item->date,
                'status' => $item->status,
                'overlaps' => $item->overlaps,
            ];
            if ($groupByCabinet) {
                // Группировка
                // cabinet_id =>
                //  week_day =>
                //      item
                //      item
                $result[$item->cabinet_id][$date->format('W')][] = $data;
            } else {
                $result[$date->format('W')][] = $data;
            }
        }

        return $result;
    }
}
