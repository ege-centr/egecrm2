<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\Admin\AdminLightResource;
use App\Models\Admin\Admin;

class Background extends Model
{
    protected $connection = 'egerep';

    public static function get()
    {
        $background = self::whereRaw("`date` = CURDATE()")->first();

        // если нынчашних нет
        if ($background === null) {
            $background = Background::whereRaw("`date` < CURDATE()")->orderBy('date', 'desc')->first();
        }

        // отображаем фон только в случае, если последний залогиненный
        // пользователь был ADMIN
        // $logged_user = isset($_COOKIE['logged_user']) ? json_decode($_COOKIE['logged_user']) : null;
        // return ($logged_user && $logged_user->type == Admin::USER_TYPE && $wallpaper) ? $wallpaper : (object)[
        //     'image_url' => 'img/background/blue.png'
        // ];
        return $background;
    }


    public function toArray()
    {
        $src = config('app.egerep-url') . 'img/wallpaper/' . $this->image;
        return [
            'src' => $src,
            'url' => "url('" . $src . "')",
            'title' => $this->title,
            'admin' => $this->user_id ? new AdminLightResource(Admin::find($this->user_id)) : null,
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function (Builder $builder) {
            return $builder->where('status', 1);
        });
    }
}
