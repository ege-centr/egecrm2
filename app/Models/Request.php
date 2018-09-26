<?php

namespace App\Models;

use Shared\Model;
use App\Traits\Enumable;

class Request extends Model
{
    use Enumable;

    protected $fillable = [
        'name', 'grade', 'comment', 'branches', 'responsible_user_id', 'subjects',
        'google_id', 'status'
    ];
    protected $commaSeparated = ['subjects', 'branches'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_user_id = User::id();
            }
        });
    }
}
