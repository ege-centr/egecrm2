<?php

use Illuminate\Database\Seeder;

class RequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('requests')->delete();
        factory(App\Models\Request::class, 10)->create()->each(function($e) {
            $e->phones()->saveMany(factory(App\Models\Phone::class, 2)->make());
        });
    }
}
