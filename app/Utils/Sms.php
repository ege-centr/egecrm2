<?php

namespace App\Utils;

use Illuminate\Support\Facades\Redis;
use Symfony\Component\Yaml\Yaml;
use App\Http\Resources\Person\PersonWithPhotoResource;
use App\Models\Email;
use App\Models\Log\{Log, LogType};
use User;

class Sms
{
	public static function sendToNumbers($numbers, $message, $isSecret = false) {
		foreach ($numbers as $number) {
			self::send($number, $message, $isSecret);
		}
	}


	public static function send($to, $message, $isSecret = false)
	{
		if (is_string($to)) {
            $to = [$to];
        }
		foreach ($to as $number) {
            if (app()->environment('staging')) {
                $number = '79268444666';
            }
			$number = \App\Utils\Phone::clean($number);
			$number = trim($number);
			if (!preg_match('/[0-9]{10}/', $number)) {
				continue;
            }

            $extra = [];

            if (User::loggedIn()) {
                $extra['created_email_id'] = User::emailId();
            }

            if ($isSecret) {
                $extra['is_secret'] = 1;
            }

            // https://smsc.ru/api/http/send/smsinfo/#menu
            $mes = $message;
            if (count($extra) > 0) {
                $mes .= "\n~~~\n" . Yaml::dump($extra);
            }

			$params = array(
                "fmt"       => 1, // 1 – вернуть ответ в виде чисел: ID и количество SMS через запятую (1234,1)
				"phones"	=> $number,
                "mes"		=> $mes,
            );

            $result = self::exec('send', $params);

            list($id, $count) = explode(',', $result);

            Log::create([
                'type' => LogType::SMS,
                'data' => [
                    'id' => $id,
                    'phone' => $number,
                    'message' => $message,
                    'is_secret' => ($isSecret ? 1 : 0),
                ],
            ]);
		}
    }

    public static function get($params)
    {

        $result = self::exec('get', array_merge($params, [
            'get_messages' => 1,
            'fmt' => 3,
            'cnt' => 1000,
        ]));
        $all_sms = json_decode($result);
        $result = [];
        if (!isset($all_sms->error)) {
            foreach($all_sms as $sms) {
                $data = [
                    'message' => $sms->message,
                    'status' => $sms->status,
                    'status_name' => $sms->status_name,
                    'phone' => $sms->phone,
                    'created_at' => date('Y-m-d H:i:s', $sms->send_timestamp),
                ];

                $extra = [];
                if (isset($sms->comment)) {
                    $extra = Yaml::parse($sms->comment);
                }

                $data['createdUser'] = isset($extra['created_email_id']) ?
                    new PersonWithPhotoResource(Email::getUser($extra['created_email_id'])) :
                    null;

                $data['is_secret'] = isset($extra['is_secret']);

                $result[] = $data;
            }
        }
        return $result;
    }

	protected static function exec($method, $params)
	{
        $params['login'] = config('sms.login');
        $params['psw'] = config('sms.psw');
        $params['charset'] = 'utf-8';
        $params['sender'] = 'EGE-Centr';
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
