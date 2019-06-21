<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contract\{Contract, ContractPayment, ContractSubject};
use App\Http\Resources\Contract\{ContractResource, ContractCollection};
use App\Http\Resources\AlgoliaResult;
use App\Models\Client\Client;
use App\Http\Requests\Contract\StoreOrUpdateRequest;

class ContractsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id', 'number'],
        'multiple' => ['year', 'grade_id', 'created_email_id'],
        'timestamp' => ['date_timestamp', 'created_at_timestamp'],
    ];

    public function index(Request $request)
    {
        $query = Contract::search()->with([
            'facets' => ['*'],
        ]);

        $this->filter($request, $query);

        if (isset($request->version)) {
            $versions = explode(',', $request->version);
            if (in_array('last', $versions)) {
                $query->where('is_active', true);
            }
            if (in_array('first', $versions)) {
                $query->where('version', 1);
            }
        }

        $result = new AlgoliaResult($query->paginateRaw($request->paginate ?: SHOW_ALL));
        $result->getCollection()->transform(function ($items, $key) {
            if ($key === 'hits') {
                foreach($items as &$item) {
                    $item['subjects'] = ContractSubject::where('contract_id', $item['id'])->get();
                    $item['client'] = new \PersonResource(Client::find($item['client_id']));
                }
            }
            if ($key === 'facets' && count($items) === 0) {
                return null;
            }
            return $items;
        });
        return $result;
    }

    public function show($id)
    {
        return new ContractResource(Contract::find($id));
    }

    public function update(StoreOrUpdateRequest $request, $id)
    {
        $item = Contract::find($id);
        $item->update($request->all());
        $item->subjects()->delete();
        $item->subjects()->createMany($request->subjects);
        $item->payments()->delete();
        $item->payments()->createMany($request->payments);
        return new ContractResource($item);
    }

    public function store(StoreOrUpdateRequest $request)
    {
        $item = Contract::create($request->all());
        $item->subjects()->createMany($request->subjects);
        $item->payments()->createMany($request->payments);
        return new ContractResource($item);
    }

    public function destroy($id)
    {
        Contract::find($id)->delete();
    }
}
