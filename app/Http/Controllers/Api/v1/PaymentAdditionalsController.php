<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment\PaymentAdditional;

class PaymentAdditionalsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'category', 'method', 'type'],
        'equals' => ['created_admin_id', 'date', 'entity_id'],
    ];

    public function index(Request $request)
    {
        $query = PaymentAdditional::query();
        $this->filter($request, $query);
        if (isset($request->entity_type) && $request->entity_type) {
            $query->where('entity_type', getModelClass($request->entity_type, true));
        }
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
