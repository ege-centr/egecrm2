<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Commentable;

class Task extends Model
{
    use Commentable;

    protected $fillable = [
        'text', 'status', 'responsible_admin_id'
    ];

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
