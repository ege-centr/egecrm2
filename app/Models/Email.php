<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $timestamps = false;
    protected $fillable = ['email', 'password'];
    protected $attributes = [
        'email' => ''
    ];

    public function setPasswordProperty($value)
    {
        $this->attributes['password'] = self::toPassword($value);
    }

    public static function toPassword(string $clean_password)
    {
        $password = md5($clean_password."_rM");
        $password = md5($password."Mr");
		return $password;
        // @todo: так делать позже
        // return md5(config('app.password_salt') . $clean_password);
    }
}
