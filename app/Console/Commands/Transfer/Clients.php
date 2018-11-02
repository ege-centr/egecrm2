<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Client\Client, Email, Phone};
use DB;

class Clients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:clients {take}';

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
        DB::table('clients')->delete();
        DB::table('client_passports')->delete();
        DB::table('client_markers')->delete();
        DB::table('contracts')->delete();
        DB::table('contract_payments')->delete();
        DB::table('contract_subjects')->delete();
        DB::table('phones')->where('entity_type', Client::class)->delete();
        DB::table('emails')->where('entity_type', Client::class)->delete();
        DB::table('comments')->where('entity_type', Client::class)->delete();

        $contract_number = 1;

        $egecrm_items = dbEgecrm('students')
            ->whereNotNull('first_name')
            ->when($take != 'all', function ($query) use ($take) {
                return $query->take($take)->orderBy('id', 'desc');
            })
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Client::create((array)$egecrm_item);
            $id = DB::table('clients')->insertGetId([
                'first_name' => $item->first_name,
                'last_name' => $item->last_name,
                'middle_name' => $item->middle_name,
                'head_teacher_id' => $item->id_head_teacher ?: null,
                'grade' => $item->grade,
                'year' => $item->year,
                'branches' => $item->branches ?: '',
                'old_student_id' => $item->id,
                'created_at' => getCurrentTime(),
                'updated_at' => getCurrentTime()
            ]);

            // representative & passport
            $representative = dbEgecrm('representatives')->whereId($item->id_representative)->first();
            $passport_exists = false;
            if ($representative) {
                $passport = dbEgecrm('passports')->whereId($representative->id_passport)->first();
                if ($passport) {
                    $passport_exists = true;
                    DB::table('client_passports')->insert([
                        'first_name' => $representative->first_name,
                        'last_name' => $representative->last_name,
                        'middle_name' => $representative->middle_name,
                        'series' => $passport->series,
                        'number' => $passport->number,
                        'code' => $passport->code,
                        'birthday' => $passport->date_birthday,
                        'issued_date' => $passport->date_issued,
                        'issued_by' => $passport->issued_by,
                        'address' => $passport->address,
                        'client_id' => $id,
                    ]);
                }
            }
            // Добавляем пустой паспорт при отсутствии
            if (! $passport_exists) {
                DB::table('client_passports')->insert([
                    'client_id' => $id,
                ]);
            }

            // Markers
            $markers = dbEgecrm('markers')->whereOwner('STUDENT')->whereType('home')->where('id_owner', $id)->get();
            foreach($markers as $marker) {
                DB::table('client_markers')->insert([
                    'lat' => $marker->lat,
                    'lng' => $marker->lng,
                    'client_id' => $id,
                ]);
            }

            // Email
            if ($item->email) {
                DB::table('emails')->insert([
                    'entity_id' => $id,
                    'entity_type' => Client::class,
                    'email' => $item->email
                ]);
            } else {
                DB::table('emails')->insert([
                    'entity_id' => $id,
                    'entity_type' => Client::class,
                    'email' => 'empty@email.ru'
                ]);
            }

            // TODO: multiple emails?
            // if ($representative && $representative->email) {
            //     DB::table('emails')->insert([
            //         'entity_id' => $id,
            //         'entity_type' => Client::class,
            //         'email' => $representative->email
            //     ]);
            // }

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
                            'entity_type' => Client::class,
                            'entity_id' => $id
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
                    'card_last_digits' => $payment->card_number ?: '',
                    'card_first_digit' => $payment->card_first_number ?: '',
                    'is_confirmed' => $payment->confirmed,
                    'bill_number' => $payment->document_number ?: null,
                    'created_at' => $payment->first_save_date,
                    'updated_at' => $payment->first_save_date,
                    'created_admin_id' => $this->getAdminId($payment->id_user),
                    'entity_type' => Client::class,
                    'entity_id' => $id,
                ]);
            }

            // Contracts
            $contracts = dbEgecrm('contract_info')->where('id_student', $item->id)->get();

            foreach($contracts as $contract) {
                $versions = dbEgecrm('contracts')->where('id_contract', $contract->id_contract)->get();
                foreach($versions as $index => $version) {
                    $contract_id = DB::table('contracts')->insertGetId([
                        'client_id' => $id,
                        'created_admin_id' => $this->getAdminId($version->id_user),
                        'year' => $contract->year,
                        'grade' => $contract->grade,
                        'sum' => $version->sum,
                        'date' => $version->date,
                        'discount' => $version->discount,
                        'version' => ($index + 1),
                        'number' => $contract->id_contract,
                        'created_at' => $version->date_changed,
                        'updated_at' => $version->date_changed,
                    ]);

                    // Contract subjects
                    $contract_subjects = dbEgecrm('contract_subjects')->where('id_contract', $version->id)->get();
                    foreach($contract_subjects as $cs) {
                        DB::table('contract_subjects')->insert([
                            'contract_id' => $contract_id,
                            'subject_id' => $cs->id_subject,
                            'lessons' => $cs->count,
                            'lessons_planned' => $cs->count_program ?: 0,
                            'status' => $this->getSubjectStatus($cs->status),
                        ]);
                    }

                    // Contract payments
                    $contract_payments = dbEgecrm('contract_payments')->where('id_contract', $version->id)->get();
                    foreach($contract_payments as $cp) {
                        DB::table('contract_payments')->insert([
                            'contract_id' => $contract_id,
                            'lessons' => $cp->lesson_count,
                            'sum' => $cp->sum,
                            'date' => $cp->date
                        ]);
                    }
                }

                // Comments
                $comments = dbEgecrm('comments')->where('place', 'STUDENT')->where('id_place', $item->id)->get();
                foreach($comments as $comment) {
                    DB::table('comments')->insert([
                        'created_admin_id' => $this->getAdminId($comment->id_user),
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

    public function getSubjectStatus($id)
    {
        switch($id) {
            case 1: return 'terminated';
            case 2: return 'to_be_terminated';
            case 3: return 'active';
        }
    }

    public function getAdminId($value)
    {
        // TODO: проверить связи
        if (\App\Models\Admin\Admin::whereId($value)->exists()) {
            return $value;
        }
        return 69;
    }
}
