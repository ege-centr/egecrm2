<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use App\Models\Request;

class RequestsWithoutClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'requests-without-clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get requests without clients';

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
        $items = Request::all();
        $this->info("Analyzing " . count($items) . "requests...");
        foreach($items as $item) {
            if (count($item->getClientIds()) <= 0) {
                $this->error($item->id);
            }
        }
    }
}
