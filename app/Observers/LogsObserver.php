<?php

namespace App\Observers;

use App\Models\Log\{Log, LogType};

class LogsObserver
{
    public function created($model)
    {
        Log::add(LogType::CREATE, $model);
    }

    public function updated($model)
    {
        Log::add(LogType::UPDATE, $model);
    }

    public function deleted($model)
    {
        Log::add(LogType::DELETE, $model);
    }
}
