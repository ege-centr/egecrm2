<?php

namespace App\Models\Contract;

use Illuminate\Database\Eloquent\Model;
use App\Models\{User, Admin\Admin};

class Contract extends Model
{
    protected $fillable = ['year', 'grade', 'sum', 'date', 'discount', 'number'];

    public function subjects()
    {
        return $this->hasMany(ContractSubject::class);
    }

    public function payments()
    {
        return $this->hasMany(ContractPayment::class);
    }

    public function createdAdmin()
    {
        return $this->belongsTo(Admin::class, 'created_admin_id');
    }

    private function setPosition()
    {
        $this->version = self::where('number', $this->number)->max('version') + 1;
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
}
