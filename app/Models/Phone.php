<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public $timestamps = false;
    protected $fillable = ['phone', 'comment'];

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            $model->phone = self::getClean($model->phone);
        });
    }

    public static function getPhoneAttribute($phone)
    {
        if ($phone[0] == '+') {
            return $phone;
        }

        $cuts = [1, 3, 3, 2, 2];

        $i = 0;
        $parts = [];
        foreach($cuts as $cut) {
            $parts[] = mb_strimwidth($phone, $i, $cut);
            $i += $cut;
        }

        return "+$parts[0] ({$parts[1]}) {$parts[2]}-{$parts[3]}-{$parts[4]}";
    }

    public function getPhoneCleanAttribute()
    {
        return $this->attributes['phone'];
    }


    public static function getClean($phone)
    {
        $phone = preg_replace("/[^0-9]/", "", $phone);
        if ($phone && $phone[0] != '7') {
            $phone = '7' . $phone;
        }
        return $phone;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'comment' => $this->comment
        ];
    }
}
