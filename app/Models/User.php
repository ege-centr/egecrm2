<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use App\Http\Resources\Admin\Resource as AdminResource;
use App\Utils\Sms;
use DB;

class User extends Model
{
    /**
     * Вход пользователя
     */
    public static function login($data)
    {
        $query = Email::where('entity_type', Admin::class)->where('email', $data['login']);

         # проверка логина
        if (! $query->exists()) {
            return self::errorResponse('неверный логин');
        }

        # проверка пароля
        $query->where('password', Email::toPassword($data['password']));
        if (! $query->exists()) {
            return self::errorResponse('неверный пароль');
        }

        $admin_id = $query->value('entity_id');
        $admin = Admin::find($query->value('entity_id'));

        # забанен ли?
        if ($admin->isBanned()) {
            return self::errorResponse('пользователь заблокирован');
        } else {
            $allowed_to_login = $admin->allowedToLogin();

            # из офиса или есть доступ вне офиса
            if ($allowed_to_login) {
                # дополнительная СМС-проверка, если пользователь логинится если не из офиса
                if ($allowed_to_login->confirm_by_sms) {
                    $sent_code = Redis::get(cacheKey('codes', $admin_id));
                    // если уже был отправлен – проверяем
                    if (! empty($sent_code)) {
                        if (@$data['code'] != $sent_code) {
                            return self::errorResponse('неверный смс-код');
                        } else {
                            Redis::del(cacheKey('codes', $admin_id));
                        }
                    } else {
                        // иначе отправляем код
                        Sms::verify($admin);
                        return (object)['data' => null, 'status' => 202];
                    }
                }
                $_SESSION['user'] = $admin;
                return (object)['data' => $admin, 'status' => 200];
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
        return isset($_SESSION["user"]) && $_SESSION["user"] 	// пользователь залогинен
            && ! User::fromSession()->isBanned()      			// и не заблокирован
            && User::fromSession()->allowedToLogin() 			// и можно входить
            && User::notChanged();      						// и данные по пользователю не изменились
	}

    /**
     * Данные по пользователю не изменились
     * если поменяли в настройках хоть что-то, сразу выкидывает, чтобы перезайти
     */
    public static function notChanged()
    {
        return User::fromSession()->updated_at == Admin::whereId(User::id())->value('updated_at');
    }

    /*
     * Пользователь из сессии
    */
    public static function fromSession()
    {
        // @todo: намана доллар сделай
        return Admin::with('photo')->find($_SESSION['user']->id);
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
