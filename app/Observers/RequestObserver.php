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

    public function creating($model)
    {
        if (User::loggedIn()) {
            $model->created_email_id = User::emailId();
        }
    }
}
