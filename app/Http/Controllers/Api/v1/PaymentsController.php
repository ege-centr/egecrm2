<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Http\Resources\Payment\Collection as PaymentCollection;

class PaymentsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'category', 'method', 'type'],
        'equals' => ['created_admin_id', 'date', 'entity_id'],
    ];

    public function index(Request $request)
    {
        $query = Payment::query();
        $this->filter($request, $query);
        $query->orderBy('id', 'desc');
        if (isset($request->entity_type) && $request->entity_type) {
            $query->where('entity_type', getModelClass($request->entity_type, true));
        }
        return PaymentCollection::collection($this->showBy($request, $query));
    }

    public function update(Request $request, $id)
    {
        $item = Payment::find($id);
        $item->update($request->input());
        return $item;
    }

    public function store(Request $request)
    {
        return Payment::create(array_merge($request->input(), [
            'entity_type' => getModelClass($request->entity_type, true)
        ]));
    }

    public function show($id)
    {
        return Payment::find($id);
    }

    public function destroy($id)
    {
        Payment::destroy($id);
    }
}
