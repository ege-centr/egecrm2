<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Log, Client\Client, Admin\Admin, Teacher};
use App\Http\Resources\Log\LogCollection;

class LogsController extends Controller
{
    protected $filters = [
        'multiple' => ['type', 'table',],
        'interval' => ['created_at'],
        'teacher' => ['teacher_id'],
        'client' => ['client_id'],
        'userType' => ['user_type'],
    ];

    public function index(Request $request)
    {
        $query = Log::with(['createdEmail', 'previewModeEmail'])->orderBy('logs.id', 'desc');
        $this->filter($request, $query);
        return LogCollection::collection(
            $this->showBy($request, $query)
        );
    }

    public function store(Request $request)
    {
        Log::create($request->all());
    }

    /**
     * Срез по логам клиента и представителя
     * используется created_email_id
     */
    protected function filterClient(string $field, $value, &$query)
    {

        $client = Client::find($value);

        $createdEmailIds = [];
        if ($client->email !== null) {
            $createdEmailIds[] = $client->email->id;
        }

        if ($client->representative->email !== null) {
            $createdEmailIds[] = $client->representative->email->id;
        }

        if (count($createdEmailIds) > 0) {
            $query->whereIn('created_email_id', $createdEmailIds);
        } else {
            // если emailIds нет, то обязательно нужно ничего не показывать
            $query->whereId(-1);
        }
    }

    /**
     * Срез по логам преподавателя
     * иппользуется created_email_id
     */
    protected function filterTeacher(string $field, $value, &$query)
    {
        $teacher = Teacher::find($value);
        if ($teacher->email !== null) {
            $query->where('created_email_id', $teacher->email->id);
        } else {
            // если emailIds нет, то обязательно нужно ничего не показывать
            $query->whereId(-1);
        }
    }

    /**
     * Срез по типу пользователя
     * (админ/клиент/ученик)
     */
    protected function filterUserType(string $field, $value, &$query)
    {
        $query->join('emails', function($join) use ($value) {
            $join->on('emails.id', '=', 'logs.created_email_id')
                ->whereIn('emails.entity_type', array_map(function($e) {
                    return getModelClass($e, true);
                }, explode(',', $value)));
        });
    }
}
