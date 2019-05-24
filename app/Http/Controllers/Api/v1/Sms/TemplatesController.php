<?php

namespace App\Http\Controllers\Api\v1\Sms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Sms;
use App\Models\Sms\SmsTemplate;

class TemplatesController extends Controller
{
    protected $filters = [
        'equals' => ['type'],
    ];

    public function index(Request $request)
    {
        $query = SmsTemplate::query();
        $this->filter($request, $query);
        return $this->showAll(
            $query
        );
    }

    public function show($id)
    {
        return SmsTemplate::find($id);
    }

    public function update($id, Request $request)
    {
        $model = SmsTemplate::find($id);
        $model->update($request->all());
        return $model;
    }
}
