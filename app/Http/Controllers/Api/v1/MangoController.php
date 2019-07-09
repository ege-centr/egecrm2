<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\IncomingCall;

class MangoController extends Controller
{
    public function call(Request $request)
    {
        $data = json_decode($request->json);
        if ($data->call_state === 'Appeared' && $data->location === 'ivr') {
            event(new IncomingCall($data));
        }
    }
}
