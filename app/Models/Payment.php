<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;

class Payment extends Model
{
    use HasCreatedAdmin;

    protected $fillable = ['category', 'type', 'method', 'date', 'sum', 'year', 'entity_type', 'entity_id'];

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
