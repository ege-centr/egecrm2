<?php

use Faker\Generator as Faker;


$factory->define(App\Models\Client\ClientPassport::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstNameFemale,
        'last_name' => $faker->lastName,
        'middle_name' => $faker->randomElement(['Николаевна', 'Игоревна', 'Эдуардовна']),
        'code' => $faker->randomNumber(3),
        'series' => $faker->randomNumber(4),
        'number' => $faker->randomNumber(6),
        'birthday' => $faker->date,
        'issued_date' => $faker->date,
        'issued_by' => 'Отделом #1 МО УФМС России',
        'address' => $faker->address
    ];
});
