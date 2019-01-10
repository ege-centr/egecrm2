<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Admin\Resource as AdminResource;
use App\Utils\Sms;
use App\Models\Admin\Admin;
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
            return self::errorResponse('неверный email');
        }

        # проверка пароля
        $query->where('password', Email::toPassword($data['password']));
        if (! $query->exists()) {
            return self::errorResponse('неверный пароль');
        }

        $entity_id = $query->value('entity_id');
        $class = $query->value('entity_type');
        $user = $class::find($entity_id);

        # забанен ли?
        if ($user->isBanned()) {
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
                            return self::errorResponse('неверный смс-код');
                        } else {
                            Redis::del(cacheKey('codes', $entity_id));
                        }
                    } else {
                        // иначе отправляем код
                        Sms::verify($user);
                        return (object)['data' => null, 'status' => 202];
                    }
                }
                $_SESSION['user'] = compact('entity_id', 'class');
                return (object)['data' => $user, 'status' => 200];
            } else {
                return self::errorResponse('нет прав доступа для данного IP');
            }
        }
        return false;
    }


    public static function logout()
    {
        unset($_SESSION['user']);
    }

    /*
	 * Проверяем, залогинен ли пользователь
	 */
	public static function loggedIn()
	{
        if (isset($_SESSION["user"]) && $_SESSION["user"]) {
            $user = User::fromSession();
            return !$user->isBanned()
                && $user->allowedToLogin() !== false
                // если админ, надо проверить, что данные по пользователю не менялись
                && (get_class($user) !== Admin::class || !$user->wasUpdated());
        }
        return false;
	}
    /*
     * Пользователь из сессии
    */
    public static function fromSession()
    {
        $class = $_SESSION['user']['class'];
        $entity_id = $_SESSION['user']['entity_id'];
        // dd([$class, $entity_id]);
        // info('loading admin...');
        // @todo: намана доллар сделай
        $user = $class::with('photo')->find($entity_id);
        $user->class = substr($class, 11);
        return $user;
        // return new AdminResource(Admin::find($_SESSION['user']->id));
    }

    public static function id()
    {
        return User::fromSession()->id;
    }

    private static function errorResponse($error_message)
    {
        return (object)[
            'data' => $error_message,
            'status' => 401,
        ];
    }
}
