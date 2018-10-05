<?php

use Faker\Generator as Faker;
use App\Models\Factory\{Branch, Grade};

$factory->define(App\Models\Client::class, function (Faker $faker) {
    return [
        'student_first_name' => $faker->firstNameMale,
        'student_last_name' => $faker->lastName,
        'student_middle_name' => $faker->randomElement(['Александрович', 'Васильевич', 'Иванович']),

        'representative_first_name' => $faker->firstNameFemale,
        'representative_last_name' => $faker->lastName,
        'representative_middle_name' => $faker->randomElement(['Николаевна', 'Игоревна', 'Эдуардовна']),

        'grade' => Grade::inRandomOrder()->value('id'),
        'year' => $faker->randomElement([2016, 2017, 2018]),
        'branches' => $faker->randomElements(Branch::pluck('id'), rand(1, 3))
    ];
});
