<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\{Client\Client, Teacher};
use DB;

class AdditionalPayments extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:additional-payments';

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
        $this->info("\n\nTransfering additional payments...");
        $tables = [
            (object)[
                'table' => 'teacher_additional_payments',
                'entity' => Teacher::class,
            ],
            (object)[
                'table' => 'student_additional_payments',
                'entity' => Client::class,
            ],
        ];

        foreach($tables as $table) {
            $egecrm_items = dbEgecrm($table->table)->get();
            $bar = $this->output->createProgressBar(count($egecrm_items));
            foreach($egecrm_items as $item) {
                if ($table->entity === Teacher::class) {
                    $entityId = $item->id_teacher;
                } else {
                    $entityId = DB::table('clients')->where('old_student_id', $item->id_student)->value('id');
                }
                $id = DB::table('payment_additionals')->insertGetId([
                    'entity_type' => $table->entity,
                    'entity_id' => $entityId,
                    'purpose' => $item->purpose,
                    'date' => $item->date,
                    'year' => $item->year,
                    'sum' => $item->sum,
                    'created_email_id' => $this->getCreatedEmailId($item->id_user),
                    'created_at' => $item->created_at,
                    'updated_at' => $item->created_at,
                ]);

                $bar->advance();
            }
            $bar->finish();
        }
    }
}
