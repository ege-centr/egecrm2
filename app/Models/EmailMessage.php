<?php

namespace App\Models;

use Shared\Model;
use App\Traits\HasCreatedAdmin;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use App\Http\Resources\Admin\Light as AdminResource;

class EmailMessage extends Model
{
    use HasCreatedAdmin;

    protected $commaSeparated = ['attachments'];
    protected $fillable = ['subject', 'message', 'email', 'attachments'];

    public static function boot()
    {
        parent::boot();

        static::creating(function($model) {
            if (User::loggedIn()) {
                $model->created_admin_id = User::id();
            }
            Mail::to($model->email)->send(new CustomEmail($model));
        });
    }

    public function toArray()
    {
        return [
            'createdAdmin' => new AdminResource($this->createdAdmin),
            'message' => $this->message,
            'attachments' => $this->attachments,
            'subject' => $this->subject,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
