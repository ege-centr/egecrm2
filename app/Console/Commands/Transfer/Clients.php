<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Client\Client, Client\Representative, Admin\Admin, Email, Phone};
use DB;

class Clients extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer clients';

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
        $this->info("\n\nTransfering clients...");
        $this->truncate('clients');
        $this->truncate('representatives');
        $this->truncate('contracts');
        $this->truncate('contract_payments');
        $this->truncate('contract_subjects');
        $this->truncateByEntity('phones', Client::class);
        $this->truncateByEntity('emails', Client::class);
        $this->truncateByEntity('emails', Representative::class);
        $this->truncateByEntity('comments', Client::class);
        $this->truncateByEntity('payments', Client::class);

        $egecrm_items = dbEgecrm('students')
            ->where('id_representative', '>', 0)
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Client::create((array)$egecrm_item);
            $id = DB::table('clients')->insertGetId([
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'middle_name' => $item->middle_name,
                'head_teacher_id' => $item->id_head_teacher ?: null,
                'grade_id' => $item->grade,
                'year' => $item->year,
                'school' => $item->school ?: '',
                'branches' => $item->branches ?: '',
                'old_student_id' => $item->id,
                'created_at' => getCurrentTime(),
                'updated_at' => getCurrentTime()
            ]);

            // берём birthdate из паспорта клиента
            $passport = dbEgecrm('passports')->whereId($item->id_passport)->first();
            if ($passport) {
                DB::table('clients')->whereId($id)->update([
                    'birthdate' => $passport->date_birthday
                ]);
            }

            // representative & passport
            $representative = dbEgecrm('representatives')->whereId($item->id_representative)->first();
            if ($representative) {
                $representativeId = DB::table('representatives')->insertGetId([
                    'first_name' => $representative->first_name,
                    'last_name' => $representative->last_name,
                    'middle_name' => $representative->middle_name,
                    'client_id' => $id,
                ]);
                $passport = dbEgecrm('passports')->whereId($representative->id_passport)->first();
                if ($passport) {
                    DB::table('representatives')->whereId($representativeId)->update([
                        'series' => $passport->series,
                        'number' => $passport->number,
                        'code' => $passport->code,
                        'birthday' => $passport->date_birthday,
                        'issued_date' => $passport->date_issued,
                        'issued_by' => $passport->issued_by,
                        'address' => $passport->address,
                    ]);
                }
            } else {
                // Добавляем пустого представителя при отсутствии
                $representativeId = DB::table('representatives')->insertGetId([
                    'client_id' => $id,
                ]);
            }

            // Email
            if ($item->email) {
                $this->insertEmail($item->email, $id, Client::class);
            }

            if ($representative && $representative->email) {
                $this->insertEmail($representative->email, $representativeId, Representative::class);
            }

            // Phones
            foreach(['phone', 'phone2', 'phone3'] as $field) {
                $phone = $item->{$field};
                if ($phone) {
                    DB::table('phones')->insert([
                        'phone' => \App\Utils\Phone::clean($phone),
                        'entity_type' => Client::class,
                        'entity_id' => $id
                    ]);
                }
                if ($representative) {
                    $phone = $representative->{$field};
                    if ($phone) {
                        DB::table('phones')->insert([
                            'phone' => \App\Utils\Phone::clean($phone),
                            'comment' => $representative->status,
                            'entity_type' => Representative::class,
                            'entity_id' => $representativeId
                        ]);
                    }
                }
            }

            // Payments
            $payments = dbEgecrm('payments')
                ->where('entity_type', 'STUDENT')
                ->where('entity_id', $item->id)
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
                    'entity_type' => Client::class,
                    'entity_id' => $id,
                ]);
            }

            // Contracts
            $contracts = dbEgecrm('contract_info')->where('id_student', $item->id)->get();

            foreach($contracts as $contract) {
                $versions = dbEgecrm('contracts')->where('id_contract', $contract->id_contract)->orderBy('date', 'asc')->get();
                foreach($versions as $index => $version) {
                    $contractId = DB::table('contracts')->insertGetId([
                        'client_id' => $id,
                        // вставляем maxflex в местах, где нет id_user
                        // потомчу что когда-то я автоматически закрыл 700 договоров
                        // у них нет id_user
                        'created_email_id' => ($version->id_user ? $this->getCreatedEmailId($version->id_user) : Admin::where('nickname', 'maxflex')->value('id')),
                        'year' => $contract->year,
                        'grade_id' => $contract->grade,
                        'sum' => $version->sum,
                        'date' => $version->date,
                        'discount' => $version->discount,
                        'number' => $contract->id_contract,
                        'created_at' => $version->date_changed,
                        'updated_at' => $version->date_changed,
                        'version' => $index + 1,
                    ]);

                    // Contract subjects
                    $contract_subjects = dbEgecrm('contract_subjects')->where('id_contract', $version->id)->get();
                    foreach($contract_subjects as $cs) {
                        DB::table('contract_subjects')->insert([
                            'contract_id' => $contractId,
                            'subject_id' => $cs->id_subject,
                            'lessons' => $cs->count ?: 0,
                            'lessons_planned' => $cs->count_program ?: 0,
                            'status' => $this->getSubjectStatus($cs->status),
                        ]);
                    }

                    // Contract payments
                    // $contract_payments = dbEgecrm('contract_payments')->where('id_contract', $version->id)->get();
                    // foreach($contract_payments as $cp) {
                    //     DB::table('contract_payments')->insert([
                    //         'contract_id' => $contractId,
                    //         'lessons' => $cp->lesson_count ?: 0,
                    //         'sum' => $cp->sum,
                    //         'date' => $cp->date ?: null,
                    //     ]);
                    // }
                }

                // Comments
                $comments = dbEgecrm('comments')->where('place', 'STUDENT')->where('id_place', $item->id)->get();
                foreach($comments as $comment) {
                    DB::table('comments')->insert([
                        'created_email_id' => $this->getCreatedEmailId($comment->id_user),
                        'text' => $comment->comment,
                        'entity_type' => Client::class,
                        'entity_id' => $id,
                        'created_at' => $comment->date,
                        'updated_at' => $comment->date,
                    ]);
                }
            }
            $bar->advance();
        }
        $bar->finish();
    }

    public function getSubjectStatus($id)
    {
        switch($id) {
            case 1: return 'terminated';
            case 2: return 'to_be_terminated';
            case 3: return 'active';
        }
    }
}
