<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contract\{Contract, ContractPayment, Contractsubject};
use App\Http\Resources\Contract\{ContractResource, ContractCollection};

class ContractsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id'],
        'multiple' => ['year', 'grade_id', 'created_admin_id'],
        'interval' => ['created_at', 'date'],
    ];

    public function index(Request $request)
    {
        $query = Contract::orderBy('date', 'desc');
        $this->filter($request, $query);

        if (isset($request->version)) {
            $versions = explode(',', $request->version);
            $query->where(function($query) use ($versions) {
                if (in_array('last', $versions)) {
                    $query->active();
                }
                if (in_array('first', $versions)) {
                    $query->where('version', 1);
                }
            });
        }

        return ContractCollection::collection($this->showBy($request, $query));
    }

    public function show($id)
    {
        return new ContractResource(Contract::find($id));
    }

    public function update(Request $request, $id)
    {
        $item = Contract::find($id);
        $item->update($request->all());
        $item->subjects()->delete();
        $item->subjects()->createMany($request->subjects);
        $item->payments()->delete();
        $item->payments()->createMany($request->payments);
        return new ContractResource($item);
    }

    public function store(Request $request)
    {
        $item = Contract::create($request->all());
        $item->subjects()->createMany($request->subjects);
        $item->payments()->createMany($request->payments);
        return new ContractResource($item);
    }

    public function destroy($id)
    {
        Contract::destroy($id);
    }
}
