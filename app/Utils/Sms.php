<?php

namespace App\Utils;

use Illuminate\Support\Facades\Redis;
use App\Models\Sms\SmsMessage;
use App\Http\Resources\SmsMessage\SmsMessageResource;

class Sms
{
	public static function sendToNumbers($numbers, $message) {
		foreach ($numbers as $number) {
			self::send($number, $message);
		}
	}


	public static function send($to, $message)
	{
		if (is_string($to)) {
            $to = [$to];
        }
		foreach ($to as $number) {
			$number = \App\Utils\Phone::clean($number);
			$number = trim($number);
			if (!preg_match('/[0-9]{10}/', $number)) {
				continue;
            }
			$params = array(
                "fmt"       => 1, // 1 – вернуть ответ в виде чисел: ID и количество SMS через запятую (1234,1)
				"phones"	=> $number,
				"mes"		=> $message,
				"sender"    => "EGE-Repetit",
            );

			$result = self::exec('send', $params);

            $sms = SmsMessage::create([
                'id' => explode(",", $result)[0],
            ]);
		}
    }

    public static function get($number, $cnt = 999)
    {
        $number = Phone::clean($number);
        $result = self::exec('get', [
            'phone' => $number,
            'get_messages' => 1,
            'fmt' => 3,
            'start' => (new \DateTime())->modify('-1 months')->format('d.m.Y'),
            'cnt' => $cnt,
        ]);
        $all_sms = json_decode($result);
        $result = [];
        if (!isset($all_sms->error)) {
            foreach($all_sms as $sms) {
                $smsMessage = SmsMessage::find($sms->id);
                $result[] = [
                    'message' => $sms->message,
                    'status' => $sms->status,
                    'status_name' => $sms->status_name,
                    'phone' => $sms->phone,
                    'model' => $smsMessage === null ? null : new SmsMessageResource(SmsMessage::find($sms->id)),
                    'created_at' => date('Y-m-d H:i:s', $sms->send_timestamp),
                ];
            }
        }
        return $result;
    }

	protected static function exec($method, $params)
	{
        $params['login'] = config('sms.login');
        $params['psw'] = config('sms.psw');
        $params['charset'] = 'utf-8';
        $ch = curl_init(config('sms.host') . $method . '.php');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$result = curl_exec($ch);
		curl_close($ch);
        return $result;
	}


	public function getStatus()
	{
		return static::textStatus($this->id_status);
	}

	/**
	 * Получить текстовый статус в зависимости от когда СМС.
	 *
	 */
	public static function textStatus($sms_status)
	{
		// Статусы тут: http://sms.ru/?panel=api&subpanel=method&show=sms/status
		switch ($sms_status) {
			case -2 : return "не доставлено";
			case 100: return "в очереди";
			case 101: return "передается оператору";
			case 102: return "в пути";
			case 103: return "доставлено";
			case 104: return "время жизни истекло";
			case 105: return "удалено оператором";
			case 106: return "сбой в телефоне";
			case 107: return "не доставлено";
			case 108: return "отклонено";
			case 207: return "недопустимый номер";
			default:  return "неизвестно";
		}
    }

    /**
     *
     */
    public static function verify($admin)
    {
        $code = mt_rand(10000, 99999);
        Redis::set(cacheKey('codes', $admin->id), $code, 'EX', 120);
        foreach($admin->phones as $phone) {
            Sms::send($phone->phone_clean, $code . ' – код для входа в ЛК', false);
        }
        return $code;
    }
}
