<?php

namespace App\Observers;

use App\Events\{CountersUpdated, NewRequest};
use User;

class RequestObserver
{
    public function updated($model)
    {
        if ($model->isDirty('status')) {
            event(new CountersUpdated);
        }
    }

    public function created($model)
    {
        event(new CountersUpdated);
        if ($model->status === 'new') {
            event(new NewRequest($model));
        }
    }
}
