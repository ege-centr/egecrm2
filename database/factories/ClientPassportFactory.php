<?php

use Faker\Generator as Faker;


$factory->define(App\Models\ClientPassport::class, function (Faker $faker) {
    return [
        'code' => $faker->randomNumber(3),
        'series' => $faker->randomNumber(4),
        'number' => $faker->randomNumber(6),
        'birthday' => $faker->date,
        'issued_date' => $faker->date,
        'issued_by' => 'Отделом #1 МО УФМС России',
        'address' => $faker->address
    ];
});
