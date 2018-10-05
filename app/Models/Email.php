<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    public $timestamps = false;
    protected $fillable = ['email', 'password'];

    public function setPasswordProperty($value)
    {
        $this->attributes['password'] = self::toPassword($value);
    }

    public static function toPassword(string $clean_password)
    {
        return md5(config('app.password_salt') . $clean_password);
    }
}
