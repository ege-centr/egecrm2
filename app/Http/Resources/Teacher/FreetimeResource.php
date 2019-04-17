<?php

namespace App\Http\Resources\Teacher;

use Illuminate\Http\Resources\Json\JsonResource;

class FreetimeResource extends JsonResource
{
    public function toArray($request)
    {
        // resources/js/components/Timeline/Day.vue
        return array_merge(parent::toArray($request), [
            'start' => $this->from ?: '10:00',
            'end' => $this->to ?: '21:00',
            'is_current' => false,
            'weekday' => $this->weekday,
            'date' => $this->getDateFromWeekday($this->weekday),
            'status' => 'conducted', // чтобы отображалось зелёным
        ]);
    }


    public function getDateFromWeekday($weekday)
    {
        return date(DATE_FORMAT, strtotime(sprintf("first %s of this year", $this->getDayName($weekday))));
    }

    private function getDayName($weekday)
    {
        switch($weekday) {
            case 1: return 'Monday';
            case 2: return 'Tuesday';
            case 3: return 'Wenesday';
            case 4: return 'Thursday';
            case 5: return 'Friday';
            case 6: return 'Saturday';
            default: return 'Sunday';
        }
    }
}
