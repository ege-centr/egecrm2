<?php

namespace App\Http\Controllers\Api\v1\Sms;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\{Sms, Phone};
use App\Http\Requests\Sms\SendRequest;

class MessagesController extends Controller
{
    public function index(Request $request)
    {
        return Sms::get([
            'phone' => Phone::clean($request->phone),
            'start' => (new \DateTime())->modify('-1 months')->format('d.m.Y'),
        ]);
    }

    public function send(SendRequest $request)
    {
        Sms::send($request->phone, $request->text);
    }
}
