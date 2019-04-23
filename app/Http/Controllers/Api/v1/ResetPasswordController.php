<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordEmail;
use App\Models\Email;
use Mail, Redis;

class ResetPasswordController extends Controller
{
    public function sendCode(Request $request)
    {
        $code = mt_rand(10000, 99999);
        Redis::set(cacheKey('reset-password', $request->email), $code, 'EX', 60 * 5);
        // Проверяем, есть ли такой email в базе
        if (Email::where('email', $request->email)->exists()) {
            Mail::to($request->email)->send(new ResetPasswordEmail($code));
            return response([
                'text' => 'код отправлен на ' . $request->email,
            ], 200);
        }
        return errorResponse('email не найден');
    }

    public function verifyCode(Request $request)
    {
        $code = Redis::get(cacheKey('reset-password', $request->email));
        if ($code !== null && $code == $request->code) {
            return response(null, 200);
        }
        return errorResponse('неверный код');
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'new_password' => ['required', 'confirmed', 'size:8']
        ]);

        $email = Email::where('email', $request->email)->first();
        $email->password = Email::toPassword($request->new_password);
        $email->save();
    }
}
