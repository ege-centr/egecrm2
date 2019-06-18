<?php

namespace App\Console\Commands\Transfer;

use Illuminate\Console\Command;
use App\Models\Settings;

class RecommendedPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transfer:recommended-prices';

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
        $this->info("\n\nTransfering recommended prices...");
        $years = [2015, 2016, 2017, 2018, 2019];
        $grades = [9, 10, 11, 14];

        $items = [];

        $id = 1;

        foreach($years as $year) {
            foreach($grades as $grade) {
                $items[] = [
                    'id' => $id,
                    'year' => $year,
                    'grade_id' => $grade,
                    'price' => 1000,
                ];
                $id++;
            }
        }

        Settings::set('recommended-prices', $items, true);
        $this->info('Recommended prices initialized!');
    }
}
