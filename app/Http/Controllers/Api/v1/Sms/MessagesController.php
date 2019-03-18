<?php

namespace App\Http\Controllers\Api\v1\Sms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Sms;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        if (isset($request->phone)) {
            return Sms::get($request->phone);
        }
        return imitatePagination(Sms::get(null));
        //return Sms::where('phone', \App\Utils\Phone::clean($request->phone))->get();
    }

    public function send(Request $request)
    {
        $sms = Sms::send($request->phone, $request->text);
        return Sms::get($request->phone, 1)[0];
    }
}
