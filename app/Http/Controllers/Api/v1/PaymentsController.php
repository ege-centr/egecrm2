<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Http\Resources\Payment\{PaymentCollection, PaymentResource};
use App\Http\Resources\AlgoliaResult;
use PersonResource;

class PaymentsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'is_confirmed'],
        'equals' => ['created_email_id', 'entity_id'],
        'timestamp' => ['date_timestamp'],
    ];

    public function index(Request $request)
    {
        $filters = [];
        foreach(['category', 'method', 'type', 'entity_type'] as $field) {
            if ($request->input($field)) {
                $values = explode(',', $request->input($field));
                $filters[] = implode(' OR ', array_map(function ($value) use ($field) {
                    return $field . ':' . $value;
                }, $values));
            }
        }

        $query = Payment::search()->with([
            'facets' => ['*'],
            'filters' => implode(' AND ', $filters)
        ]);

        $this->filter($request, $query);
        $result = new AlgoliaResult($query->paginateRaw($request->paginate));
        $result->getCollection()->transform(function ($items, $key) {
            if ($key === 'hits') {
                foreach($items as &$item) {
                    $entity = getEntity($item['entity_type'], $item['entity_id']);
                    $item['entity'] = $entity ? new PersonResource(getEntity($item['entity_type'], $item['entity_id'])) : null;
                }
            }
            if ($key === 'facets' && count($items) === 0) {
                return null;
            }
            return $items;
        });
        return $result;
    }

    public function update(Request $request, $id)
    {
        $item = Payment::find($id);
        $item->update($request->input());
        return $item;
    }

    public function store(Request $request)
    {
        return Payment::create($request->all());
    }

    public function show($id)
    {
        return new PaymentResource(Payment::find($id));
    }

    public function destroy($id)
    {
        Payment::destroy($id);
    }
}
