<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $result = Settings::get($request->key, $request->json);
        return $result ? response()->json($result) : null;
    }

    public function store(Request $request)
    {
        Settings::set($request->key, $request->value, $request->json);
    }
}
