<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client\EgeTrial;

class EgeTrialsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id']
    ];

    public function index(Request $request)
    {
        $query = EgeTrial::query();
        $this->filter($request, $query);
        return $this->showAll($query);
    }

    public function update($id, Request $request)
    {
        $model = EgeTrial::find($id);
        $model->update($request->all());
        return $model;
    }

    public function store(Request $request)
    {
        return EgeTrial::create($request->all());
    }

    public function show($id)
    {
        return EgeTrial::find($id);
    }
}
