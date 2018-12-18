<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedAdmin;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use App\Http\Resources\Admin\Light as AdminResource;

class EmailMessage extends Model
{
    use HasCreatedAdmin;

    protected $fillable = ['subject', 'message', 'email'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_admin_id = User::id();
            }
            Mail::to($model->email)->send(new CustomEmail($model->message, $model->subject));
        });
    }

    public function toArray()
    {
        return [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'message' => $this->message,
            'subject' => $this->subject,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
