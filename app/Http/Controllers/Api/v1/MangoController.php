<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Mango\{
    IncomingCall,
    AnsweredCall
};

class MangoController extends Controller
{
    public function call(Request $request)
    {
        // $data = json_decode($request->json);
        $data = jsonRedecode($request->data);
        logger(json_encode($data));
        // logger(json_encode($request->input('data')));

        // Обрабатываем ЕГЭ-Центр
        if (@$data->to->line_number === '74956468592') {
            if ($data->call_state === 'Appeared' && $data->location === 'ivr') {
                event(new IncomingCall($data));
            }

            if ($data->call_state === 'Connected') {
                event(new AnsweredCall($data));
            }
        }
    }
}
