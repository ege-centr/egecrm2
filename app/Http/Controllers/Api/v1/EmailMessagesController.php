<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{EmailMessage, File};
use App\Http\Resources\EmailMessage\EmailMessageResource;
use App\Http\Requests\EmailMessage\StoreRequest;
use User;

class EmailMessagesController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailMessage::orderBy('id', 'desc')->where('email', $request->email);

        if (! User::isAdmin()) {
            $query->where('created_email_id', User::emailId());
        }

        return EmailMessageResource::collection($query->get());
    }

    public function store(StoreRequest $request)
    {
        foreach($request->emails as $email) {
            $emailMessage = new EmailMessage($request->all());
            $emailMessage->email = $email;
            $emailMessage->save();
            if ($request->input('files') !== null) {
                foreach($request->input('files') as $file) {
                    $emailMessage->files()->create($file);
                }
            }
            $emailMessage = $emailMessage->fresh();
            $emailMessage->send();
        }
        return response(null, 201);
        // return new EmailMessageResource($emailMessage);
    }
}
