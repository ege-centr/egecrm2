<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Settings;
use App\Events\StagingSyncFinished;

class FinishStagingSync extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'staging-sync:finish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finish staging sync';

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
        Settings::set('is_syncing_staging', 0);
        event(new StagingSyncFinished);
    }
}
