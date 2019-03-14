<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasCreatedEmail, HasFiles};
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use App\Http\Resources\Admin\Light as AdminResource;
use User;

class EmailMessage extends Model
{
    use HasCreatedEmail, HasFiles;

    protected $fillable = ['subject', 'message', 'email'];

    public function send()
    {
        Mail::to($this->email)->send(new CustomEmail($this));
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_email_id = User::emailId();
        });
    }
}
