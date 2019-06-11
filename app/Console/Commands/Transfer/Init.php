<?php

namespace App\Console\Commands\Transfer;

class Init extends TransferCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all tables before transfer';

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
        $skip = ['migrations', 'settings', 'sms_templates'];
        $tables = array_map('reset', \DB::select('SHOW TABLES'));

        $bar = $this->output->createProgressBar(count($tables));
        foreach($tables as $table) {
            $bar->advance();
            if (in_array($table, $skip)) {
                continue;
            }
            $this->truncate($table);
        }
        $bar->finish();
    }
}
