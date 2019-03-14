<?php

namespace App\Traits;

use App\Models\{User, Admin\Admin, Teacher, Client, Email};

trait HasCreatedEmail
{
    public function getCreatedUserAttribute()
    {
        if ($this->createdEmail !== null) {
            return $this->createdEmail->user;
        }
        return null;
    }

    public function createdEmail()
    {
        return $this->belongsTo(Email::class, 'created_email_id');
    }

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
