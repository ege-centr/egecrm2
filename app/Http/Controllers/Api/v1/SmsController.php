<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sms;

class SmsController extends Controller
{
    public function index(Request $request)
    {
        return Sms::where('phone', \App\Utils\Phone::clean($request->phone))->get();
    }

    public function send(Request $request)
    {
        return \App\Utils\Sms::send($request->phone, $request->text);
    }
}
