<?php

namespace App\Models;

use Shared\Model;
use App\Traits\{HasEmail, HasPhoto, HasPhones};

class Admin extends Model
{
    use HasEmail, HasPhoto, HasPhones;

    protected $commaSeparated = ['rights'];

    public $fillable = [
        'id', // только на время переноса
        'first_name', 'last_name', 'middle_name', 'salary', 'rights'
    ];

    public function ips()
    {
        return $this->hasMany(AdminIp::class);
    }

    public function isBanned()
    {
        // @todo
        return false;
        // return $this->allowed(\Shared\Rights::WSTAT_BANNED);
    }

    public function allowed($right)
    {
        return in_array($right, $this->rights);
    }

    /**
	 * Можно ли логиниться с этого IP?
	 */
	public function allowedToLogin()
	{
        if (app('env') === 'local') {
            return (object)[
                'confirm_by_sms' => false
            ];
        }

        $current_ip = ip2long($_SERVER['HTTP_X_REAL_IP']);
        foreach($this->ips as $ip) {
            $ip_from = ip2long(trim($ip->ip_from));
            $ip_to = ip2long(trim($ip->ip_to ?: $ip->ip_from));
            if ($current_ip >= $ip_from && $current_ip <= $ip_to) {
                return $ip;
            }
        }

        return false;
	}
}
