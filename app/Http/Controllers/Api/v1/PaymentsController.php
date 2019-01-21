<?php

namespace App\Http\Controllers\api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Http\Resources\Payment\Collection as PaymentCollection;

class PaymentsController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query();

        if (isset($request->sort_by) && $request->sort_by) {
            $query->orderBy($request->sort_by, $request->sort_type)->orderBy('id', 'desc');
        }

        foreach(['year', 'category', 'method', 'type'] as $field) {
            if (isset($request->{$field}) && $request->{$field}) {
                $query->whereIn($field, explode(',', $request->{$field}));
            }
        }

        foreach(['created_admin_id', 'date', 'entity_id'] as $field) {
            if (isset($request->{$field}) && $request->{$field}) {
                $query->where($field, $request->{$field});
            }
        }

        if (isset($request->entity_type) && $request->entity_type) {
            $query->where('entity_type', getModelClass($request->entity_type, true));
        }

        return PaymentCollection::collection($query->paginate($request->show_by ?: 9999));
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
            'entity_type' => getModelClass($request->class, true)
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
