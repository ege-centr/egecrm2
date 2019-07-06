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
    Teacher\TeacherCollection,
    Request\RequestCollection
};

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $results = [];
        foreach([
            Teacher::class,
            Client::class,
            ClientRequest::class,
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
        $ids = array_merge(
            Client::searchByName($text)->pluck('id')->all(),
            Representative::searchByName($text)->pluck('client_id')->all(),
            Phone::search($text)->entity(Client::class)->pluck('entity_id')->all()
        );

        return ClientCollection::collection(
            Client::whereIn('id', $ids)->take(100)->get()
        );
    }

    private function tutors(string $text)
    {
        $ids = array_merge(
            Teacher::searchByName($text)->pluck('id')->all(),
            Phone::search($text)->entity(Teacher::class)->pluck('entity_id')->all()
        );

        return TeacherCollection::collection(
            Teacher::whereIn('id', $ids)->get()
        );
    }

    private function requests(string $text)
    {
        $ids = Phone::search($text)->entity(ClientRequest::class)->pluck('entity_id')->all();

        $items = ClientRequest::query()
            ->withCount('comments')
            ->whereIn('id', $ids)
            ->take(30)
            ->orderByRaw("
                if(comments_count > 0, 1, 0) desc,
                created_at desc
            ")
            ->get();

        $filteredItems = [];
        foreach($items as $item) {
            if ($item->isGrouped) {
                continue;
            }
            $relativeIds = $item->getRelativeIds();
            if (count($relativeIds) > 0) {
                $items->transform(function ($i) use ($relativeIds) {
                    if (in_array($i->id, $relativeIds)) {
                        $i->isGrouped = true;
                    }
                    return $i;
                });
            }
            $filteredItems[] = $item->id;
        }


        // return $filteredItems;

        return RequestCollection::collection(
            ClientRequest::whereIn('id', $filteredItems)->orderBy('created_at', 'desc')->get()
        );
    }
}
