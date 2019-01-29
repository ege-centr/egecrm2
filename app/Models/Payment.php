<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;

class Payment extends Model
{
    use HasCreatedAdmin;

    protected $fillable = ['category', 'type', 'method', 'date', 'sum', 'year', 'entity_type', 'entity_id', 'card_number'];

    // public function setCardNumberAttribute($value)
    // {
    //     return preg_replace('/[^0-9]/', '', $value);
    // }

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
