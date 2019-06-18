<?php

namespace App\Models\Payment;

use MyCLabs\Enum\Enum;

class PaymentMethod extends Enum
{
    const DISABLE_LOGS = true;

    const CARD = 'card';
    const CASH = 'cash';
    const BILL = 'bill';
    const CARD_ONLINE = 'card_online';
    const MUTUAL = 'mutual';
}
