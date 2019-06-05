<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Phone, Admin\Admin};

class PhonesController extends Controller
{
    public function checkDuplicate(Request $request)
    {
        $query = Phone::query()
            ->where('entity_type', '<>', Admin::class)
            ->where('entity_type', '<>', \App\Models\Request::class)
            ->where('phone', \App\Utils\Phone::clean($request->phone));

        if (isset($request->id)) {
            $query->where('id', '<>', $request->id);
        }

        return response()->json([
            'data' => $query->first()
        ]);
    }
}
