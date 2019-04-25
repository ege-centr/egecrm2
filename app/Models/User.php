<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Admin\Resource as AdminResource;
use App\Utils\Sms;
use App\Models\{Admin\Admin, Client\Client, Teacher, Email, Log};
use App\Utils\SessionService;

use DB;

class User extends Model
{
    /**
     * Вход пользователя
     */
    public static function login($data)
    {
        $query = Email::where('email', $data['login']);

         # проверка логина
        if (! $query->exists()) {
            self::authLog('неверный логин', ['email' => $data['login']]);
            return self::errorResponse('неверный email');
        }

        # проверка пароля
        $query->where('password', Email::toPassword($data['password']));
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
                        if (@$data['code'] != $sent_code) {
                            self::authLog('неверный смс-код');
                            return self::errorResponse('неверный смс-код');
                        } else {
                            Redis::del(cacheKey('codes', $entity_id));
                        }
                    } else {
                        // иначе отправляем код
                        Sms::verify($user);
                        self::authLog('', [], 'sms_code_sent');
                        return (object)['data' => null, 'status' => 202];
                    }
                }
                $_SESSION['user'] = compact('entity_id', 'class');
                SessionService::action();
                self::authLog('', [], 'success');
                return (object)['data' => $user, 'status' => 200];
            } else {
                self::authLog('нет прав доступа для данного IP');
                return self::errorResponse('нет прав доступа для данного IP');
            }
        }
        return false;
    }


    public static function logout()
    {
        if (isset($_SESSION["user"]) && $_SESSION["user"]) {
            SessionService::destroy();
            unset($_SESSION['user']);
        }
    }

    /*
	 * Проверяем, залогинен ли пользователь
	 */
	public static function loggedIn()
	{
        if (isset($_SESSION["user"]) && $_SESSION["user"]) {
            if (User::isInPreviewMode() || SessionService::exists()) {
                $user = User::fromSession();
                return !$user->isBanned()
                    && $user->allowedToLogin() !== false
                    // если админ, надо проверить, что данные по пользователю не менялись
                    && (get_class($user) !== Admin::class || !$user->wasUpdated());
            }
        }
        return false;
	}
    /*
     * Пользователь из сессии
    */
    public static function fromSession()
    {
        if (isset($_SESSION["user"]) && $_SESSION["user"]) {
            $class = $_SESSION['user']['class'];
            $entity_id = $_SESSION['user']['entity_id'];
            // dd([$class, $entity_id]);
            // info('loading admin...');
            // TODO: намана доллар сделай
            $query = $class::query();
            if ($class !== Teacher::class) {
                $query->with('photo');
            }
            $user = $query->find($entity_id);
            $user->class = trimModelClass($class);
            $user->entity_type = $class;
            $user->class_basename = class_basename($class);
            return $user;
            // return new AdminResource(Admin::find($_SESSION['user']->id));
        }
        return null;
    }

    public static function id()
    {
        return User::fromSession()->id;
    }

    public static function emailId()
    {
        return Email::where('entity_type', $_SESSION['user']['class'])
            ->where('entity_id', $_SESSION['user']['entity_id'])
            ->value('id');
    }

    public static function isTeacher()
    {
        return get_class(self::fromSession()) === Teacher::class;
    }

    public static function isClient()
    {
        return get_class(self::fromSession()) === Client::class;
    }

    public static function isAdmin()
    {
        return get_class(self::fromSession()) === Admin::class;
    }

    private static function errorResponse($error_message)
    {
        return (object)[
            'data' => $error_message,
            'status' => 401,
        ];
    }

    public static function isInPreviewMode()
    {
        return isset($_SESSION['real_user']);
    }

    /**
     * Кастом лог авторизации
     */
    private static function authLog($message = '', $data = [], $type = 'fail')
    {
        Log::create([
            'type' => Log::TYPE_AUTH,
            'data' => array_merge($data, [
                $type => $message,
                'user_agent' => @$_SERVER['HTTP_USER_AGENT'],
            ])
        ]);
    }
}
