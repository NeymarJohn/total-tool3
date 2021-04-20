<?php


$factory->define(\App\Lead::class, function (Faker\Generator $faker) {

    return [
        'company_name' => $faker->company,
        'website' => $faker->name,
        'client_name' => $faker->name,
        'client_email' => $faker->unique()->safeEmail,
        'mobile' => '123456789',
        'note' => '123456789',
    ];
});
