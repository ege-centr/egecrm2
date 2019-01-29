<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Request as ClientRequest,
    Group\Group,
    Client\Client,
    Teacher
};
use App\Http\Resources\{
    Client\Collection as ClientCollection,
    Teacher\Collection as TeacherCollection
};

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $results = [];
        foreach([
            Teacher::class,
            Client::class,
        ] as $class) {
            $table = (new $class)->getTable();
            $results[$table] = $this->{$table}($request->text);
        }
        return $results;
    }

    /**
     * Типы поиска
     */
    private function clients(string $text)
    {
        return ClientCollection::collection(
            Client::searchByName($text)->get()
        );
    }

    private function tutors(string $text)
    {
        return TeacherCollection::collection(
            Teacher::searchByName($text)->get()
        );
    }
}
