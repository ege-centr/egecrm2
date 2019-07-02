<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Client\Client, Request as ClientRequest, Phone};
use App\Http\Resources\Request\{RequestResource, RequestCollection};
use App\Http\Resources\AlgoliaResult;
use App\Http\Requests\Request\StoreOrUpdateRequest;

class RequestsController extends Controller
{
    protected $filters = [
        'multiple' => ['grade_id', 'responsible_admin_id', 'created_email_id'],
        'timestamp' => ['created_at_timestamp'],
    ];

    public function index(Request $request)
    {
        if (isset($request->client_id)) {
            return RequestCollection::collection(
                $this->showAll(Client::find($request->client_id)->requests())
            );
        }

        $filters = [];
        foreach(['status'] as $field) {
            if ($request->input($field)) {
                $values = explode(',', $request->input($field));
                $filters[] = implode(' OR ', array_map(function ($value) use ($field) {
                    return $field . ':' . $value;
                }, $values));
            }
        }

        $query = ClientRequest::search()->with([
            'facets' => ['*'],
            'filters' => implode(' AND ', $filters)
        ]);

        $this->filter($request, $query);

        $result = new AlgoliaResult(
            $query->paginateRaw($request->paginate ?: SHOW_ALL)
        );
        $result = jsonRedecode($result);
        $ids = collect($result->data)->pluck('id');

        $items = ClientRequest::whereIn('id', $ids)->with(['responsibleAdmin', 'createdEmail'])->orderBy('id', 'desc')->get();
        $result->data = RequestCollection::collection($items);
        if (is_array($result->facets) && empty($result->facets)) {
            $result->facets = (object) [];
        }
        return response()->json($result);
    }

    public function store(StoreOrUpdateRequest $request)
    {
        $new_model = ClientRequest::create($request->input());
        $new_model->phones()->createMany($request->phones);

        // если заявка падает с сайта
        // и среди на текущий момент "новых" заявок есть телефон только что упавшей заявки,
        // то только что упавшая заявка должна иметь статус "выполненные"
        if (isset($request->google_id) && isset($request->phones)) {
            $newRequestIds = ClientRequest::where('status', 'new')->where('id', '<>', $new_model->id)->pluck('id')->all();
            if (
                Phone::query()
                    ->where('entity_type', ClientRequest::class)
                    ->whereIn('entity_id', $newRequestIds)
                    ->where('phone', $new_model->phones->first()->phone_clean)
                    ->exists()
            ) {
                $new_model->status = 'finished';
                $new_model->save();
            }
        }

        return response(new RequestResource($new_model), 201);
    }

    public function show($id)
    {
        return new RequestResource(ClientRequest::find($id));
    }

    public function update(StoreOrUpdateRequest $request, $id)
    {
        $model = ClientRequest::find($id);
        $model->update($request->input());

        $model->phones()->delete();
        $model->phones()->createMany($request->phones);

        return new RequestCollection($model->fresh());
    }

    public function destroy($id)
    {
        ClientRequest::find($id)->delete();
    }
}
