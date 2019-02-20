<?php

namespace App\Models\Payment;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;
use App\Models\User;

class Payment extends Model
{
    use HasCreatedAdmin;

    protected $fillable = [
        'category', 'type', 'method', 'date', 'sum', 'year', 'entity_type', 'entity_id', 'card_number', 'is_confirmed'
    ];

    public function entity()
    {
        return $this->morphTo();
    }

    public function getClassNameAttribute($value)
    {
        return trim(self::class, 'App\\Models\\');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_admin_id = User::id();
            }
        });

        static::created(function ($model) {
            // TODO: bill_number
        });
    }
}
