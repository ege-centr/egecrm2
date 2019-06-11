<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Client\Client};
use App\Models\Factory\Subject;
use DB;

class AnonymousPayments extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:anonymous-payments';

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

        $egecrm_items = dbEgecrm('payments')
            ->whereRaw('(entity_id is null or entity_id = 0)')
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $payment) {
            $extra = json_decode($payment->extra);
            // {"last_name":"Гуляченков","first_name":"Артем","middle_name":"Георгиевич","phone":"+7 (967) 146-43-01","id_subject":"1","profile_link":"","email":"artgul23.01@mail.ru"}
            // Создаем клиента
            $clientId = DB::table('clients')->insertGetId([
                'first_name' => isset($extra->first_name) ? $extra->first_name : '',
                'last_name' => isset($extra->last_name) ? $extra->last_name : '',
                'middle_name' => isset($extra->middle_name) ? $extra->middle_name : '',
                'school' => isset($extra->id_subject) ? Subject::getTitle($extra->id_subject, 'name') : '',
                'created_email_id' => $this->getCreatedEmailId($payment->id_user),
                'created_at' => $payment->first_save_date,
                'updated_at' => $payment->first_save_date,
            ]);

            if (isset($extra->email) && $extra->email) {
                $this->insertEmail($extra->email, $clientId, Client::class);
            }

            if (isset($extra->phone) && $extra->phone) {
                DB::table('phones')->insert([
                    'phone' => \App\Utils\Phone::clean($extra->phone),
                    'entity_type' => Client::class,
                    'entity_id' => $clientId
                ]);
            }

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
                'entity_type' => Client::class,
                'entity_id' => $clientId,
            ]);


            $bar->advance();
        }
        $bar->finish();
    }

    public function getStatus($value)
    {
        // const NEWR		       	    = 1;
		// const UPLOADED_PRODUCTION	= 5;
		// const FINISHED		        = 6;
		// const DEBUG 		        = 7;
		// const CLOSED 	 	        = 8;
        switch($value) {
            case 1: return 'new';
            case 5: return 'testing';
            case 6: return 'done';
            case 7: return 'debug';
            default: return 'closed';
        }
    }
}
