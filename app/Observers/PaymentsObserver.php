<?php

namespace App\Observers;

use App\Models\Payment\{Payment, PaymentType, PaymentMethod};
use App\Models\Client\Client;

class PaymentsObserver
{
    public function creating(Payment $payment)
    {
        if (
            $payment->type === PaymentType::PAYMENT &&
            $payment->method === PaymentMethod::CASH &&
            $payment->entity_type === Client::class
        ) {
            $payment->bill_number = Payment::max('bill_number') + 1;
        }
    }
}
