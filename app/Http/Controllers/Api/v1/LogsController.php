<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Http\Resources\Log\LogCollection;

class LogsController extends Controller
{
    protected $filters = [
        'multiple' => ['type', 'table'],
    ];

    public function index(Request $request)
    {
        $query = Log::with(['createdEmail', 'previewModeEmail'])->orderBy('id', 'desc');
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
