<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Person\PersonResource;

class PersonController extends Controller
{
    public function index(Request $request)
    {
        $class = getModelClass($request->class);
        return new PersonResource($class::find($request->id));
    }
}
