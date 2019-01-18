<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use DB;

class Cabinets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:cabinets';

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
        DB::table('cabinets')->delete();

        $egecrm_items = dbEgecrm('cabinets')->get();

        $bar = $this->output->createProgressBar(count($egecrm_items));
        foreach($egecrm_items as $item) {
            // $new_item = Task::create((array)$egecrm_item);
            DB::table('cabinets')->insert([
                'id' => $item->id,
                'branch_id' => $item->id_branch,
                'number' => $item->number,
            ]);
            $bar->advance();
        }
        $bar->finish();
    }
}
