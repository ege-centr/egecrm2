<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MangoController extends Controller
{
    public function index(Request $request)
    {
        logger(json_encode($request->all()), JSON_PRETTY_PRINT);
    }
}
