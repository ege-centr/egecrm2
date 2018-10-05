<?php

use Faker\Generator as Faker;
use App\Models\Factory\{Branch, Subject, Grade};
use App\Models\{Request, Admin};

$factory->define(App\Models\Request::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'grade' => Grade::inRandomOrder()->value('id'),
        'branches' => $faker->randomElements(Branch::pluck('id'), rand(1, 3)),
        'subjects' => $faker->randomElements(Subject::pluck('id'), rand(1, 3)),
        'status' => $faker->randomElement(Request::getEnumValues('status')),
        'responsible_admin_id' => $faker->randomElement(Admin::where('id', '<', 100)->pluck('id')),
        'created_admin_id' => rand(0, 2) ? $faker->randomElement(Admin::where('id', '<', 100)->pluck('id')) : null
    ];
});
