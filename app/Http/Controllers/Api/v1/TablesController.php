<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;

class TablesController extends Controller
{
    public function index(Request $request)
    {
        return Log::whereNotNull('table')->groupBy('table')->orderBy('table', 'asc')->pluck('table')->all();
    }
}
