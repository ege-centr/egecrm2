<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{EmailMessage, File};
use App\Http\Resources\EmailMessage\EmailMessageResource;

class EmailMessagesController extends Controller
{
    public function index(Request $request)
    {
        return EmailMessageResource::collection(
            EmailMessage::orderBy('id', 'desc')
                ->where('email', $request->email)
                ->get()
        );
    }

    public function store(Request $request)
    {
        foreach($request->emails as $email) {
            $emailMessage = new EmailMessage($request->all());
            $emailMessage->email = $email;
            $emailMessage->save();
            foreach($request->input('files') as $file) {
                $emailMessage->files()->create($file);
            }
            $emailMessage = $emailMessage->fresh();
            $emailMessage->send();
        }
        return response(null, 201);
        // return new EmailMessageResource($emailMessage);
    }
}
