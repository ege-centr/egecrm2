<?php

namespace App\Models\Sms;

use MyCLabs\Enum\Enum;


/**
 * Action enum
 */
class SmsTemplateCode extends Enum
{
    const DISABLE_LOGS = true;

    const CLIENT_LESSON_SKIPPED = 'CLIENT_LESSON_SKIPPED';
    const CLIENT_LESSON_LATE = 'CLIENT_LESSON_LATE';
    const LESSON_CONDUCTED = 'LESSON_CONDUCTED';
    const REPORT_BECAME_AVAILABLE = 'REPORT_BECAME_AVAILABLE';
}
