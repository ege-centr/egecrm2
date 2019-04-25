<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment\Payment;
use App\Http\Resources\Payment\{PaymentCollection, PaymentResource};

class PaymentsController extends Controller
{
    protected $filters = [
        'multiple' => ['year', 'category', 'method', 'type', 'is_confirmed'],
        'equals' => ['created_email_id', 'entity_id'],
        'interval' => ['date'],
    ];

    public function index(Request $request)
    {
        $query = Payment::query();
        $this->filter($request, $query);
        if (isset($request->entity_type) && $request->entity_type) {
            $query->whereIn('entity_type', array_map(function($e) {
                return getModelClass($e, true);
            }, explode(',', $request->entity_type)));
        }
        $query->orderBy('id', 'desc');
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
