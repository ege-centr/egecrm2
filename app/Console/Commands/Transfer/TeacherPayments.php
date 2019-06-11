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
    protected $signature = 'transfer:teacher-payments {take}';

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
        $take = $this->argument('take');
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
                    'card_last_digits' => $payment->card_number ?: '',
                    'card_first_digit' => $payment->card_first_number ?: '',
                    'is_confirmed' => $payment->confirmed,
                    'bill_number' => $payment->document_number ?: null,
                    'created_at' => $payment->first_save_date,
                    'updated_at' => $payment->first_save_date,
                    'created_email_id' => $this->getCreatedEmailId($payment->id_user),
                    'entity_id' => $teacher_id,
                ]);
            }

            $bar->advance();
        }
        $bar->finish();
    }

    public function getPaymentMethod($id)
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

    public function getPaymentCategory($id)
    {
        switch($id) {
            case 1: return 'study';
            case 2: return 'career_guidance';
            case 3: return 'ege_trial';
        }
    }
}
