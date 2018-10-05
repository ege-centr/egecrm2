<?php

use Faker\Generator as Faker;
use App\Models\Phone;

$factory->define(Phone::class, function (Faker $faker) {
    return [
        'phone' => Phone::getClean($faker->e164PhoneNumber),
        'comment' => $faker->randomElement(['основной', 'мобильный', 'домашний', 'рабочий'])
    ];
});
