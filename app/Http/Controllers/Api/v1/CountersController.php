<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use App\Models\{
    Request as ClientRequest,
    Lesson\Lesson,
    Group\Group,
    Client\Client
};

class CountersController extends Controller
{
    public function index(Request $request)
    {
        $counters = [];
        foreach([
            ClientRequest::class,
            Group::class,
            Client::class,
            Lesson::class,
        ] as $class) {
            $table = (new $class)->getTable();
            $query = $class::query();
            $counters[$table] = $this->{$table}($query)->count();
        }
        return $counters;
    }

    private function requests(Builder &$query)
    {
        // TODO: добавить enum библиотеку
        return $query->where('status', 'new');
    }

    private function groups(Builder &$query)
    {
        return $query->where('year', academicYear());
    }

    private function clients(Builder &$query)
    {
        return $query;
    }

    private function lessons(Builder &$query)
    {
        return $query->notRegistered();
    }
}
