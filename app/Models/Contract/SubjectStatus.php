<?php

namespace App\Models\Contract;

use MyCLabs\Enum\Enum;

class SubjectStatus extends Enum
{
    const DISABLE_LOGS = true;

    const ACTIVE = 'active';
    const TO_BE_TERMINATED = 'to_be_terminated';
    const TERMINATED = 'terminated';
}
