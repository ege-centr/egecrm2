<?php

namespace App\Observers;

use App\Events\ReportBecameAvailable;
use App\Models\{User, Report\Report};

class ReportsObserver
{
    public function updated($model)
    {
        $changes = $model->getChanges();
        if (isset($changes['is_available_for_parents']) && $model->is_available_for_parents) {
            event(new ReportBecameAvailable($model));
        }
    }

    public function creating($model)
    {
        if (User::loggedIn()) {
            $model->created_email_id = User::emailId();
        }
    }
}
