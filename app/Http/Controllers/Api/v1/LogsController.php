<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Log, Client\Client, Admin\Admin, Teacher};
use App\Http\Resources\Log\LogCollection;

class LogsController extends Controller
{
    protected $filters = [
        'multiple' => ['type', 'table'],
        'interval' => ['created_at'],
    ];

    public function index(Request $request)
    {
        $query = Log::with(['createdEmail', 'previewModeEmail'])->orderBy('logs.id', 'desc');

        if (isset($request->user_type)) {
            $query->join('emails', function($join) use ($request) {
                $join->on('emails.id', '=', 'logs.created_email_id')
                    ->whereIn('emails.entity_type', array_map(function($e) {
                        return getModelClass($e, true);
                    }, explode(',', $request->user_type)));
            });
        }

        $this->filter($request, $query);
        return LogCollection::collection(
            $this->showBy($request, $query)
        );
    }

    public function store(Request $request)
    {
        Log::create($request->all());
    }
}
