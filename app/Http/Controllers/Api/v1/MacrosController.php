<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MacrosController extends Controller
{
    public function index(Request $request)
    {
        $path = base_path() . '/resources/views/print';
        $files = scandir($path);
        $files = array_diff(scandir($path), array('.', '..'));
        $files = array_values($files);

        foreach($files as &$file) {
            $file = str_replace('.blade.php', '', $file);
        }

        return [
            'data' => $files,
            'meta' => [
                'current_page' => 1,
                'last_page' => 1,
            ],
        ];
    }

    public function show($filename)
    {
        return file_get_contents(base_path() . '/resources/views/print/' . $filename . '.blade.php');
    }

    public function update($id, Request $request)
    {
        file_put_contents(base_path() . '/resources/views/print/' . $id . '.blade.php', $request->text);
    }
}
