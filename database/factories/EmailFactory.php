<?php

use Faker\Generator as Faker;
use App\Models\Email;

$factory->define(Email::class, function (Faker $faker) {
    return [
        'email' => $faker->email,
        'password' => str_random(10),
    ];
});
