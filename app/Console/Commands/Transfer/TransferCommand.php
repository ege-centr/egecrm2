<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use DB, Schema;
use App\Models\{Email, Teacher, Payment\PaymentAdditional};

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

    protected function getCreatedEmailId($userId)
    {
        $admin = \App\Models\Admin\Admin::where('old_id', $userId)->first();
        if ($admin) {
            return $admin->email->id;
        } else {
            // не нашелся админ – пытаемся найти по учителю
            $teacher = dbEgecrm('users')->where('id_entity', $userId)->where('type', 'TEACHER')->first();
            if ($teacher) {
                return optional(Teacher::find($teacher->id_entity)->email)->id;
            }
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
            case 6: return 'mutual';
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

    protected function getCardNumber($payment)
    {
        if (isset($payment->card_number) && !empty($payment->card_number)) {
            if (isset($payment->card_first_number) && !empty($payment->card_first_number)) {
                return $payment->card_first_number . $payment->card_number;
            } else {
                return '0' . $payment->card_number;
            }
        }
        return null;
    }

    protected function createAdditionalPaymentIfNeeded($payment, $entityType, $entityId)
    {
        $category = $this->getPaymentCategory($payment->category);
        if (in_array($category, ['career_guidance', 'ege_trial'])) {
            DB::table('payment_additionals')->insert([
                // 'entity_type' => $payment->entity_type == 'TEACHER' ? Teacher::class : Client::class,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'sum' => $payment->sum,
                'purpose' => ($category === 'ege_trial' ? 'Пробный ЕГЭ' : 'Профориентация'),
                'date' => $payment->date,
                'year' => $payment->year,
                'created_email_id' => $this->getCreatedEmailId($payment->id_user),
                'created_at' => $payment->first_save_date,
                'updated_at' => $payment->first_save_date,
            ]);
        }
    }
}
