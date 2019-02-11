<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class PreviewController extends Controller
{
    public function index(Request $request)
    {
        $_SESSION['real_user'] = $_SESSION['user'];
        $_SESSION['user'] = [
            'entity_id' => $request->entity_id,
            'class' => getModelClass($request->class_name, true),
        ];
        $user = User::fromSession();
        $user->preview = true;
        return $user;
    }

    public function exit()
    {
        $_SESSION['user'] = $_SESSION['real_user'];
        unset($_SESSION['real_user']);
        return User::fromSession();
    }
}
