<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settings;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        return response()->json(Settings::get($request->key, isset($request->json)));
    }

    public function store(Request $request)
    {
        Settings::set($request->key, $request->value, isset($request->json));
    }
}
