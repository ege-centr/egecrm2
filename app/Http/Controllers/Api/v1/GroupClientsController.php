<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group\{Group, GroupClient};

class GroupClientsController extends Controller
{
    public function store(Request $request)
    {
        GroupClient::create($request->all());
        Group::find($request->group_id)->searchable();
    }

    public function destroy(Request $request)
    {
        // TODO: это почему-то не работает с touches
        GroupClient::query()
            ->where('group_id', $request->group_id)
            ->where('client_id', $request->client_id)
            // ->first()
            ->delete();
        Group::find($request->group_id)->searchable();
    }
}
