<?php

use Faker\Generator as Faker;
use App\Models\Factory\{Branch, Grade};

$factory->define(App\Models\Client\Client::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstNameMale,
        'last_name' => $faker->lastName,
        'middle_name' => $faker->randomElement(['Александрович', 'Васильевич', 'Иванович']),

        'grade' => Grade::inRandomOrder()->value('id'),
        'year' => $faker->randomElement([2016, 2017, 2018]),
        'branches' => $faker->randomElements(Branch::pluck('id'), rand(1, 3))
    ];
});
