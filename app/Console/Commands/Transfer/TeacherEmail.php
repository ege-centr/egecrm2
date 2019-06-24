<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\Teacher;
use DB;

class TeacherEmail extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:teacher-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Transfer teacher emails from EGEREP';

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
        $this->info("\n\nTransfering teacher emails...");
        $this->truncateByEntity('emails', Teacher::class);

        $egecrm_items = dbEgerep('tutors')
            ->where('in_egecentr', '>', 0)
            ->select('id', 'email')
            // TODO: по-хорошему у всех преподов должны быть emails
            // ->where('email', '<>', '')
            ->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            if ($item->email && !DB::table('emails')->where('email', $item->email)->exists()) {
                $email = $item->email;
            } else {
                $email = "teacher-{$item->id}@empty.ru";
            }
            DB::table('emails')->insert([
                'email' => $email,
                'entity_type' => Teacher::class,
                'entity_id' => $item->id,
            ]);
            $bar->advance();
        }
        $bar->finish();
    }
}
