<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Lesson\Lesson, Lesson\ClientLesson, Teacher, Client\Client, Group\GroupClient};
use App\Http\Resources\Lesson\{LessonResource,  ClientLessonInSchedule, ClientLessonCollection};

class ClientLessonsController extends Controller
{
    protected $filters = [
        'entity' => ['entity_type'],
        'equals' => ['entity_id', 'status'],
    ];

    public function index(Request $request)
    {
        $query = ClientLesson::orderBy('date', 'asc')->orderBy('time', 'asc');

        if (isset($request->status) && $request->status === 'conducted') {
            $this->filter($request, $query);
        } else {
            // Вместе с планируемыми
            // AND `date` >= DATE(NOW())
            $group_ids = GroupClient::where('client_id', $request->entity_id)->pluck('group_id')->implode(',');
            $query->withoutGlobalScope('clients')->whereRaw("
                (entity_type = '" . addslashes(Client::class) . "' AND entity_id = {$request->entity_id}) OR
                (entity_type IS NULL AND group_id IN ({$group_ids}))
            ");
        }

        return ClientLessonCollection::collection($query->get());
    }

    public function update(Request $request, $id)
    {
        $model = Lesson::find($id);
        $model->update($request->all());
        foreach($request->clientLessons as $clientLesson) {
            ClientLesson::find($clientLesson['id'])->update($clientLesson);
        }
        return new LessonResource($model);
    }

    /**
     * Add client to existing lesson
     */
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required',
            'client_id' => 'required',
        ]);

        $lesson = Lesson::find($request->lesson_id);
        $client_lesson = new ClientLesson($lesson->toArray());
        foreach($lesson->toArray() as $field => $value) {
            $client_lesson->{$field} = $value;
        }
        unset($client_lesson['id']);
        unset($client_lesson['price']);
        $client_lesson->created_at = now()->format('Y-m-d H:i:s');
        $client_lesson->entity_id = $request->client_id;
        $client_lesson->entity_type = Client::class;
        $client_lesson->save();
        return new ClientLessonInSchedule($client_lesson);
    }

    public function destroy($id)
    {
        ClientLesson::find($id)->delete();
    }
}
