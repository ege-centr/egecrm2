<?php

namespace App\Models\Payment;

use MyCLabs\Enum\Enum;

class PaymentType extends Enum
{
    const DISABLE_LOGS = true;

    const PAYMENT = 'payment';
    const RETURN = 'return';
}
