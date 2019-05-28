<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ReCaptcha\ReCaptcha;
use App\Models\{User, Email};
use App\Models\Log\{Log, LogType};
use App\Http\Resources\Admin\AdminResource;
use App\Utils\{SessionService, Sms};
use Illuminate\Support\Facades\Redis;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        /**
         * Проверка капчи
         */
        // $recaptcha = new ReCaptcha(config('captcha.secret'));
        // $resp = $recaptcha->verify($request->token, @$_SERVER['HTTP_X_REAL_IP']);
        // if (! $resp->isSuccess()) {
        //     return response()->json('captcha error', 403);
        // }
        $credentials = (object) $request->credentials;
        $query = Email::where('email', $credentials->login);

         # проверка логина
        if (! $query->exists()) {
            self::authLog('неверный логин', ['email' => $credentials->login]);
            return self::errorResponse('неверный email');
        }

        # проверка пароля
        $query->where('password', Email::toPassword($credentials->password));
        if (! $query->exists()) {
            self::authLog('неверный пароль');
            return self::errorResponse('неверный пароль');
        }

        $entity_id = $query->value('entity_id');
        $class = $query->value('entity_type');
        $user = $class::find($entity_id);

        # забанен ли?
        if ($user->isBanned()) {
            self::authLog('пользователь заблокирован');
            return self::errorResponse('пользователь заблокирован');
        } else {
            $allowed_to_login = $user->allowedToLogin();

            # из офиса или есть доступ вне офиса
            if ($allowed_to_login !== false) {
                # дополнительная СМС-проверка, если пользователь логинится если не из офиса
                if ($allowed_to_login !== true && $allowed_to_login->confirm_by_sms) {
                    $sent_code = Redis::get(cacheKey('codes', $entity_id));
                    // если уже был отправлен – проверяем
                    if (! empty($sent_code)) {
                        if (@$credentials->code != $sent_code) {
                            self::authLog('неверный смс-код');
                            return self::errorResponse('неверный смс-код');
                        } else {
                            Redis::del(cacheKey('codes', $entity_id));
                        }
                    } else {
                        // иначе отправляем код
                        Sms::verify($user);
                        self::authLog('', [], 'sms_code_sent');
                        return response(null, 202);
                    }
                }
                $_SESSION['user'] = compact('entity_id', 'class');
                SessionService::action();
                self::authLog('', [], 'success');
                // возвращаем пользователя для lastLoggedUser
                return response(new AdminResource($user), 200);
            } else {
                self::authLog('нет прав доступа для данного IP');
                return self::errorResponse('нет прав доступа для данного IP');
            }
        }
    }

    public function logout()
    {
        if (isset($_SESSION["user"]) && $_SESSION["user"]) {
            SessionService::destroy();
            unset($_SESSION['user']);
        }
    }

    public function confirmPassword(Request $request)
    {
        return response()->json(
            Email::where('entity_id', User::id())
                ->where('entity_type', getModelClass(User::fromSession()->class, true))
                ->where('password', Email::toPassword($request->password))
                ->exists()
        );
    }

    /**
     * Кастом лог авторизации
     */
    private static function authLog($message = '', $data = [], $type = 'fail')
    {
        Log::create([
            'type' => LogType::AUTH,
            'data' => array_merge($data, [
                $type => $message,
                'user_agent' => @$_SERVER['HTTP_USER_AGENT'],
            ])
        ]);
    }

    private static function errorResponse($error_message)
    {
        return response(['data' => $error_message], 422);
    }
}
