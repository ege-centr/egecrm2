<?php

namespace App\Models\Sms;

use Illuminate\Database\Eloquent\Model;
use App\Utils\Sms;

class SmsTemplate extends Model
{
    public $timestamps = false;
    protected $fillable = ['text', 'title'];

    public static function send(
        $to,
        SmsTemplateCode $code,
        array $variables = []
    )
    {
        $text = self::where('code', $code)->value('text');

        foreach($variables as $variable => $value) {
            $text = str_replace('{' . $variable .'}', $value, $text);
        }
        Sms::send($to, $text);
    }
}
