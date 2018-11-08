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
        return PaymentCollection::collection(Payment::paginate(30));
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

    public function destroy($id)
    {
        Payment::destroy($id);
    }
}
