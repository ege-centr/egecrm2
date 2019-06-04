<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Admin\Admin, Client\Client, Factory\Grade};
use App\Traits\HasCreatedEmail;
use Laravel\Scout\Searchable;

class Contract extends Model
{
    use HasCreatedEmail, Searchable;

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
     * Предыдущая версия договора
     */
    public function previous()
    {
        return $this->belongsTo(self::class, 'number', 'number')->where('version', $this->version - 1);
    }

    public function getGradeAttribute()
    {
        return Grade::find($this->grade_id);
    }

    public function getDiscountedSumAttribute()
    {
        if ($this->discount > 0) {
            return round($this->sum - ($this->sum * ($this->discount / 100)));
        }
        return $this->sum;
    }

    public function toSearchableArray()
    {
        return extractFields($this, [
            'id', 'number', 'date', 'sum', 'grade_id', 'discount',
            'year', 'client_id', 'version', 'is_active', 'created_email_id'
        ], [
            'date_timestamp' => strtotime($this->date),
            'created_at_timestamp' => strtotime($this->created_at),
        ]);
    }

    /**
     * Активная (текущая) версия договора
     * должна быть последняя в цепи версий
     */
    public function getIsActiveAttribute()
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

    /**
     * Получить последнюю цепь договора в рамках года
     *
     */
    public function scopeLastInYear($query, $year)
    {
        return $query->whereRaw("number = (select max(c2.number) from contracts c2 where c2.client_id = contracts.client_id and c2.year = {$year})");
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (! $model->number) {
                $model->number = self::max('number') + 1;
            }
            if (User::loggedIn()) {
                $model->created_email_id = User::emailId();
            }
            $model->setPosition();
        });
    }

    private function setPosition()
    {
        $this->version = self::chain($this->number)->max('version') + 1;
    }
}
