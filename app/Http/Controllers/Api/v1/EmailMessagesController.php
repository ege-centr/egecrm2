<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{EmailMessage, File};

class EmailMessagesController extends Controller
{
    public function index(Request $request)
    {
        return EmailMessage::where('email', $request->email)->get();
    }

    public function store(Request $request)
    {
        $emailMessage = EmailMessage::create($request->all());
        foreach($request->input('files') as $file) {
            $emailMessage->files()->create($file);
        }
        $emailMessage->send();
        return $emailMessage;
    }
}
