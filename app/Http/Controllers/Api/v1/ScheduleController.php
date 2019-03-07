<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\ClientLesson, Client\Client, Group\GroupClient};
use App\Http\Resources\Lesson\ClientLessonCollection;

class ScheduleController extends Controller
{
    public function client($id)
    {
        // Уроки
        $query = ClientLesson::orderBy('date', 'asc')->orderBy('time', 'asc');
        $group_ids = GroupClient::where('client_id', $id)->pluck('group_id')->implode(',');
        $query->withoutGlobalScope('clients')->whereRaw("
            (entity_type = '" . addslashes(Client::class) . "' AND entity_id = {$id}) OR
            (entity_type IS NULL AND group_id IN ({$group_ids}))
        ");

        return ClientLessonCollection::collection($query->get());
    }
}
