<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Request as ClientRequest,
    Group\Group,
    Client\Client,
    Client\Representative,
    Teacher,
    Phone
};
use App\Http\Resources\{
    Client\ClientCollection,
    Teacher\TeacherCollection
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
        $ids = [];

        $ids = array_merge($ids, Client::searchByName($text)->pluck('id')->all());
        $ids = array_merge($ids, Representative::searchByName($text)->pluck('client_id')->all());
        $ids = array_merge($ids, Phone::search($text)->where('entity_type', Client::class)->pluck('entity_id')->all());

        return ClientCollection::collection(
            Client::whereIn('id', $ids)->get()
        );
    }

    private function tutors(string $text)
    {
        $ids = [];

        $ids = array_merge($ids, Teacher::searchByName($text)->pluck('id')->all());
        $ids = array_merge($ids, Phone::search($text)->where('entity_type', Teacher::class)->pluck('entity_id')->all());

        return TeacherCollection::collection(
            Teacher::whereIn('id', $ids)->get()
        );
    }
}
