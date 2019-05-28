<?php

namespace App\Models\Log;

use MyCLabs\Enum\Enum;

class LogType extends Enum
{
    const DISABLE_LOGS = true;

    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const AUTH = 'auth';
    const URL = 'url';
    const SMS = 'sms';
}
