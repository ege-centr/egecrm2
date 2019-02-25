<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\File;

class DownloadController extends Controller
{
    public function download($id)
    {
        $file = File::find($id);
        $file_path = public_path() . "/storage/img/upload/" . $file->name;
        return response()->download($file_path, $file->original_name);
    }
}
