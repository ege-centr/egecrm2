<?php

use Illuminate\Database\Seeder;
use App\Models\{Settings, Factory\Year};

class RecommendedPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $grades = [9, 10, 11, 14];
        $data = [];
        foreach(Year::ALL as $year) {
            foreach($grades as $grade) {
                $data[$year][$grade] = null;
            }
        }
        Settings::set('recommended-prices', $data, true);
    }
}
