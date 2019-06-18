<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\Teacher;
use DB;

class TeacherPayments extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:teacher-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("\n\nTransfering teacher payments...");
        $this->truncateByEntity('payments', Teacher::class);

        $teacher_ids = Teacher::pluck('id');

        $bar = $this->output->createProgressBar(count($teacher_ids));
        foreach($teacher_ids as $teacher_id) {

            // Payments
            $payments = dbEgecrm('payments')
                ->where('entity_type', 'TEACHER')
                ->where('entity_id', $teacher_id)
                ->get();

            foreach($payments as $payment) {
                DB::table('payments')->insert([
                    'sum' => $payment->sum,
                    'date' => $payment->date,
                    'year' => $payment->year,
                    'method' => $this->getPaymentMethod($payment->id_status),
                    'type' => $payment->id_type == 1 ? 'payment' : 'return',
                    'category' => $this->getPaymentCategory($payment->category),
                    'card_number' => $this->getCardNumber($payment),
                    'is_confirmed' => $payment->confirmed,
                    'bill_number' => $payment->document_number ?: null,
                    'created_at' => $payment->first_save_date,
                    'updated_at' => $payment->first_save_date,
                    'created_email_id' => $this->getCreatedEmailId($payment->id_user),
                    'entity_type' => Teacher::class,
                    'entity_id' => $teacher_id,
                ]);

                $this->createAdditionalPaymentIfNeeded($payment, Teacher::class, $teacher_id);
            }

            $bar->advance();
        }
        $bar->finish();
    }
}
