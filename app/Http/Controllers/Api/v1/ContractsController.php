<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Contract\{Contract, ContractPayment, Contractsubject};
use App\Http\Resources\Contract\Resource as ContractResource;

class ContractsController extends Controller
{
    protected $filters = [
        'equals' => ['client_id'],
        'multiple' => ['year'],
    ];

    public function index(Request $request)
    {
        $query = Contract::query();
        $this->filter($request, $query);
        return ContractResource::collection($this->showBy($request, $query));
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
