<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use DB, Schema;
use App\Models\Email;

class TransferCommand extends Command
{

    protected $signature = 'transfer:commandddd';

    protected $description = '';

    protected function truncate($table)
    {
        Schema::disableForeignKeyConstraints();
        DB::table($table)->truncate();
        Schema::enableForeignKeyConstraints();
    }

    protected function truncateByEntity($table, $entity)
    {
        DB::table($table)->where('entity_type', $entity)->delete();
    }

    protected function getCreatedEmailId($oldAdminId)
    {
        $admin = \App\Models\Admin\Admin::where('old_id', $oldAdminId)->first();
        if ($admin) {
            return $admin->email->id;
        }
        return null;
    }

    protected function getAdminId($oldAdminId)
    {
        $admin = \App\Models\Admin\Admin::where('old_id', $oldAdminId)->first();
        if ($admin !== null) {
            return $admin->id;
        }
        return null;
    }

    protected function insertEmail($emailString, $entityId, $entityType)
    {
        $email = Email::where('email', $emailString)->first();
        if ($email !== null) {
            // часто у представителя одиин и тот же email с учеником
            // в этом случае не создаём email представителя
            // $this->error(sprintf(
            //     "\nDuplicate emails: \n%s\n%s\n%s\n\n%s\n%s\n%s",
            //     $email->email,
            //     $email->entity_type,
            //     $email->entity_id,
            //     $emailString,
            //     $entityType,
            //     $entityId
            // ));
            return;
        }
        DB::table('emails')->insert([
            'entity_id' => $entityId,
            'entity_type' => $entityType,
            'email' => $emailString
        ]);
    }


    protected function getPaymentMethod($id)
    {
		// const PAID_CARD		= 1;
		// const PAID_CASH		= 2;
		// const PAID_BILL		= 4;
		// const CARD_ONLINE	= 5;
		// const MUTUAL_DEBTS	= 6;
        switch($id) {
            case 1: return 'card';
            case 2: return 'cash';
            case 4: return 'bill';
            case 5: return 'card_online';
        }
    }

    protected function getPaymentCategory($id)
    {
        switch($id) {
            case 1: return 'study';
            case 2: return 'career_guidance';
            case 3: return 'ege_trial';
        }
    }
}
