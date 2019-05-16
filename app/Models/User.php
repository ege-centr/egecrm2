<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Resources\User\UserWithPhotoResource;
use App\Models\{Admin\Admin, Client\Client, Teacher, Email};
use App\Utils\SessionService;


use DB;

class User extends Model
{
    /*
	 * Проверяем, залогинен ли пользователь
	 */
	public static function loggedIn()
	{
        if (isset($_SESSION["user"]) && $_SESSION["user"]) {
            if (User::isInPreviewMode()) {
                return true;
            }
            if (SessionService::exists()) {
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
            return new UserWithPhotoResource($user);
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

    public static function isInPreviewMode()
    {
        return isset($_SESSION['real_user']);
    }
}
