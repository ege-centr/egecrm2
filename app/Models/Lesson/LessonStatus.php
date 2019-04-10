<?php

namespace App\Models\Lesson;

use MyCLabs\Enum\Enum;

class LessonStatus extends Enum
{
    const DISABLE_LOGS = true;

    const CONDUCTED = 'conducted';
    const CANCELLED = 'cancelled';
    const PLANNED = 'planned';
}
