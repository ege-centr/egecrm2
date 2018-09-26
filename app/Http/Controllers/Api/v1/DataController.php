<?php

namespace App\Http\Controllers\Api\v1;

use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DataController extends Controller
{
    /**
     * Статические данные из Models/Data
     */
    public function static(Request $request)
    {
        $data = [];
        foreach($request->fields as $class) {
            $data[$class] = Cache::remember(cacheKey('static-data', $class), ONE_MONTH, function() use ($class) {
                $class = "\\App\\Models\Data\\" . ucfirst($class);
                return $class::all();
            });
        }

        return response()->json($data);
    }

    /**
     * ENUM значения из модели
     */
    public function enum(Request $request)
    {
        $data = Cache::remember(cacheKey('enum-data', $request->class), ONE_MONTH, function() use ($request) {
            $class = "\\App\\Models\\" . ucfirst($request->class);
            return $class::getEnumValues($request->field);
        });

        return response()->json($data);
    }
}
