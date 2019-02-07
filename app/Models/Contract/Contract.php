<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Admin\Admin, Client\Client};
use App\Traits\HasCreatedAdmin;

class Contract extends Model
{
    use HasCreatedAdmin;

    protected $fillable = ['year', 'grade_id', 'sum', 'date', 'discount', 'number', 'client_id'];

    public function subjects()
    {
        return $this->hasMany(ContractSubject::class);
    }

    public function payments()
    {
        return $this->hasMany(ContractPayment::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Активная (текущая) версия договора
     * должна быть последняя в цепи версий
     */
    public function isActive()
    {
        return $this->version == self::chain($this->number)->max('version');
    }

    /**
     * Цепочка договоров
     */
    public function scopeChain($query, $number)
    {
        return $query->whereNumber($number);
    }

    /**
     * Активные договоры
     */
    public function scopeActive($query)
    {
        return $query->whereRaw('id = (select max(c2.id) from contracts c2 where c2.number = contracts.number)');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (! $model->number) {
                $model->number = self::max('number') + 1;
            }
            if (User::loggedIn()) {
                $model->created_admin_id = User::id();
            }
            $model->setPosition();
        });
    }

    private function setPosition()
    {
        $this->version = self::chain($this->number)->max('version') + 1;
    }
}
