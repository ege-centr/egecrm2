<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\{HasCreatedAdmin, HasFiles};
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use App\Http\Resources\Admin\Light as AdminResource;

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
}
