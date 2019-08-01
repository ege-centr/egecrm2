<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Client\{Client, Representative};
use DB;

class EmptyEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:empty-emails';

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
        $this->info("Clients");

        foreach([Client::class, Representative::class] as $class) {
            $models = $class::doesntHave('email')->get();
            $bar = $this->output->createProgressBar(count($models));

            foreach($models as $model) {
                $model->email()->create(['email' => null]);
                $bar->advance();
            }
            $bar->finish();
            $this->info("\n");
        }
    }
}
