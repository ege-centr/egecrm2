<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Request, Phone};
use App\Models\Client\{Client, Representative};

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
        $ids = [];
        $this->info("Analyzing " . count($items) . " requests...");
        foreach($items as $item) {
            $requestHasNumber = false;
            foreach($item->phones as $phone) {
                if ($requestHasNumber) {
                    break;
                }
                foreach([Client::class, Representative::class] as $class) {
                    if (Phone::where('entity_type', $class)->where('phone', $phone->phone_clean)->exists()) {
                        $requestHasNumber = true;
                        break;
                    }
                }
            }
            if (! $requestHasNumber) {
                $ids[] = $item->id;
            }
        }
        file_put_contents('test.txt', implode(', ', $ids));
    }
}
