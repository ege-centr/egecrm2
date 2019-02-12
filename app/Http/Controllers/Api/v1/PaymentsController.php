<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Http\Resources\Payment\PaymentCollection;

class PaymentsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'category', 'method', 'type'],
        'equals' => ['created_admin_id', 'date', 'entity_id'],
    ];

    public function index(Request $request)
    {
        $query = Payment::orderBy('id', 'desc');
        $this->filter($request, $query);
        if (isset($request->entity_type) && $request->entity_type) {
            $query->whereIn('entity_type', array_map(function($e) {
                return getModelClass($e, true);
            }, explode(',', $request->entity_type)));
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
