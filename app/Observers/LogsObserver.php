<?php

namespace App\Observers;

use App\Models\Log;

class LogsObserver
{
    public function created($model)
    {
        Log::add(Log::TYPE_CREATE, $model);
    }

    public function updated($model)
    {
        Log::add(Log::TYPE_UPDATE, $model);
    }

    public function deleted($model)
    {
        Log::add(Log::TYPE_DELETE, $model);
    }
}
