<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentAdditional;

class PaymentAdditionalsController extends Controller
{
    protected $filters = [
        'multiple' => ['year'],
        'equals' => ['entity_id'],
        'entity' => ['entity_type'],
    ];

    public function index(Request $request)
    {
        $query = PaymentAdditional::query();
        $this->filter($request, $query);
        return $this->showAll($query);
    }

    public function update(Request $request, $id)
    {
        $item = PaymentAdditional::find($id);
        $item->update($request->input());
        return $item;
    }

    public function store(Request $request)
    {
        return PaymentAdditional::create(array_merge($request->input(), [
            'entity_type' => getModelClass($request->entity_type, true)
        ]));
    }

    public function show($id)
    {
        return PaymentAdditional::find($id);
    }

    public function destroy($id)
    {
        PaymentAdditional::destroy($id);
    }
}
