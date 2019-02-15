<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    const UPLOAD_PATH = 'public/img/upload/';

    public function store(Request $request)
    {
        $extension = $request->file->extension();
        $original_name = $request->file->getClientOriginalName();
        $name = uniqid() . '.' . $extension;
        $request->file->storeAs(self::UPLOAD_PATH, $name);
        return compact('name', 'original_name');
    }
}
