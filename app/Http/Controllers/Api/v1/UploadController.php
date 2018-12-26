<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    const UPLOAD_PATH = 'public/img/upload/';

    public function store(Request $request)
    {
        $filename = uniqid() . '.' . $request->file->extension();
        $request->file->storeAs(self::UPLOAD_PATH, $filename);
        return $filename;
    }
}
