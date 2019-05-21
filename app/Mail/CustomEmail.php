<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\EmailMessage;
use App\Http\Controllers\Api\v1\UploadController;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(EmailMessage $message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->message->subject)->from(config('mail.from.address'), config('mail.from.name'))->view("mail.custom")->with(['html' => $this->message->message]);
        foreach($this->message->files as $file) {
            $mail->attach(storage_path() . '/app/' . UploadController::UPLOAD_PATH . $file->name, [
                'as' => $file->original_name,
            ]);
        }
        return $mail;
    }
}
