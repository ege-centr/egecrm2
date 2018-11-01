<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;

    public static function set($key, $value, $json = false)
    {
        $query = static::where('key', $key);
        if ($json) {
            $value = json_encode($value);
        }
        if ($query->exists()) {
            $query->update([
                'value' => $value
            ]);
        } else {
            static::insert(['key' => $key, 'value' => $value]);
        }
    }

    public static function get($key, $json = false)
    {
        $value = static::where('key', $key)->value('value');
        if ($json) {
            return json_decode($value);
        }
        return $value;
    }
}
