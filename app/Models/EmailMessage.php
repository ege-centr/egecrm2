<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasCreatedAdmin, HasFiles};
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use App\Http\Resources\Admin\Light as AdminResource;
use User;

class EmailMessage extends Model
{
    use HasCreatedAdmin, HasFiles;

    protected $fillable = ['subject', 'message', 'email'];

    public function send()
    {
        Mail::to($this->email)->send(new CustomEmail($this));
    }

    public function toArray()
    {
        return [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'message' => $this->message,
            'files' => $this->files,
            // 'attachments' => $this->attachments,
            'subject' => $this->subject,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }

    public static function boot()
    {
        parent::boot();

        // TODO:  изменить на created_email_id
        static::creating(function ($model) {
            if (User::isTeacher()) {
                $model->created_admin_id = 1;
            } else {
                $model->created_admin_id = User::id();
            }
        });
    }
}
