<?php

namespace App\Http\Controllers\Api\v1\Sms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\{Sms, Phone};

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        return Sms::get([
            'phone' => Phone::clean($request->phone),
            'start' => (new \DateTime())->modify('-1 months')->format('d.m.Y'),
        ]);
    }

    public function send(Request $request)
    {
        $sms = Sms::send($request->phone, $request->text);
        // return Sms::get($request->phone, 1)[0];
    }
}
