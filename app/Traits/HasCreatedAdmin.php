<?php

namespace App\Traits;

use App\Models\{User, Admin\Admin};

trait HasCreatedAdmin
{
    public function createdAdmin()
    {
        return $this->belongsTo(Admin::class, 'created_admin_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_admin_id = User::id();
            }
        });
    }
}
