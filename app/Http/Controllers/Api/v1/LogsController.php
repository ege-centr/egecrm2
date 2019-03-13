<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Http\Resources\Log\LogCollection;

class LogsController extends Controller
{
    protected $filters = [
        'multiple' => ['type'],
    ];

    public function index(Request $request)
    {
        $query = Log::orderBy('id', 'desc');
        $this->filter($request, $query);
        return LogCollection::collection(
            $this->showBy($request, $query)
        );
    }
}
