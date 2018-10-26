<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Log;

class LogsController extends Controller
{
    public function index(Request $request)
    {
        return Log::orderBy('id', 'desc')->get();
    }
}
