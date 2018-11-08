<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group\GroupClient;

class GroupClientsController extends Controller
{
    public function store(Request $request)
    {
        GroupClient::create($request->all());
    }
}
