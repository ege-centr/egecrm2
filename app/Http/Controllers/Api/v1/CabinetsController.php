<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cabinet;

class CabinetsController extends Controller
{
    public function index(Request $request)
    {
        return Cabinet::all();
    }
}
