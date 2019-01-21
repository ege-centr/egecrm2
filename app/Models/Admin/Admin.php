<?php

namespace App\Models\Admin;

use Shared\{Model, Rights};
use App\Interfaces\UserInterface;
use Illuminate\Database\Eloquent\Builder;
use App\Traits\{HasEmail, HasPhoto, HasPhones};

class Admin extends Model implements UserInterface
{
    use HasEmail, HasPhoto, HasPhones;

    protected $commaSeparated = ['rights'];

    public $fillable = [
        // 'id', // только на время переноса
        'first_name', 'last_name', 'middle_name', 'salary', 'rights', 'nickname'
    ];

    public function ips()
    {
        return $this->hasMany(AdminIp::class);
    }

    public function isBanned()
    {
        return !$this->allowed(Rights::LK2_HAS_ACCESS);
    }

    public function allowed($right)
    {
        return in_array($right, $this->rights);
    }

    /**
     * Если данные изменились, должен перезалогиниться (решили в целях безопасности)
     */
    public function wasUpdated()
    {
        return $this->updated_at != self::whereId($this->id)->value('updated_at');
    }

    /**
	 * Можно ли логиниться с этого IP?
	 */
	public function allowedToLogin()
	{
        if (app('env') === 'local' || app('env') == 'testing') {
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

    public static function boot()
    {
        parent::boot();

        static::addGlobalScope('defaultOrder', function(Builder $builder) {
            $builder
                ->orderByRaw("IF(FIND_IN_SET(" . Rights::LK2_HAS_ACCESS . ", rights) > 0, 1, 0) desc")
                ->orderBy('id', 'asc');
        });
    }
}
