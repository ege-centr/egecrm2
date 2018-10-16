<?php

use Illuminate\Database\Seeder;

class CabinetsSeeder extends Seeder
{
    const CABINETS = [
        1 =>	[305,321,322,304,320,319,310,311,314,303,302,301],
        2 =>	[809],
        6 =>	[1],
        8 =>	[507],
        11 =>	[10],
        12 =>	[1],
        13 =>	['035'],
        14 =>	[1],
        15 =>	[1],
        16 =>	[1],
        17 =>	[221],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cabinets')->truncate();
        foreach(self::CABINETS as $branch_id => $cabinets) {
            foreach($cabinets as $number) {
                DB::table('cabinets')->insert(compact('branch_id', 'number'));
            }
        }
    }
}