<?php

namespace App\Observers;

use App\Events\CountersUpdated;

class RequestObserver
{
    public function updated($model)
    {
        if ($model->isDirty('status')) {
            event(new CountersUpdated);
        }
    }
}
