<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public $timestamps = false;
    protected $fillable = ['phone', 'comment'];

    public static function getPhoneAttribute($phone)
    {
        return \App\Utils\Phone::format($phone);
    }

    public function getPhoneCleanAttribute()
    {
        return $this->attributes['phone'];
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'phone' => $this->phone,
            'comment' => $this->comment
        ];
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function($model) {
            $model->phone = \App\Utils\Phone::clean($model->phone);
        });
    }
}
