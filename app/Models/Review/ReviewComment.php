<?php

namespace App\Models\Review;

use Illuminate\Database\Eloquent\Model;
use User;

class ReviewComment extends Model
{
    protected $fillable = ['rating', 'text', 'type'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_email_id = User::emailId();
            }
        });
    }
}
