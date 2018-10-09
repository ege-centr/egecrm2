<?php

use Illuminate\Database\Seeder;

class ClientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->delete();
        factory(App\Models\Client\Client::class, 10)->create()->each(function($e) {
            $e->passport()->save(factory(App\Models\Client\ClientPassport::class)->make());
            $e->email()->save(factory(App\Models\Email::class)->make());
            $e->phones()->saveMany(factory(App\Models\Phone::class, 2)->make());
        });
    }
}
