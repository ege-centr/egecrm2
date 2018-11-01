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
}
