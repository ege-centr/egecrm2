<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\Sms;

class SmsController extends Controller
{
    public function index(Request $request)
    {
        return Sms::get($request->phone);
        //return Sms::where('phone', \App\Utils\Phone::clean($request->phone))->get();
    }

    public function send(Request $request)
    {
        $sms = Sms::send($request->phone, $request->text);
        return Sms::get($request->phone, 1)[0];
    }
}
