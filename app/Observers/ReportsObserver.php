<?php

namespace App\Observers;

use App\Models\Report\Report;
use App\Events\ReportBecameAvailable;

class ReportsObserver
{
    public function updated($model)
    {
        $changes = $model->getChanges();
        if (isset($changes['is_available_for_parents']) && $model->is_available_for_parents) {
            event(new ReportBecameAvailable($model));
        }
    }
}
