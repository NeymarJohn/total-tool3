<?php


$factory->define(\App\User::class, function (Faker\Generator $faker) {
    static $mainCompany;
    static $departments, $designations, $locations, $managers;

    if (!$mainCompany) {
        $mainCompany = \App\Company::first();
    }

    return [
        'company_id' => $mainCompany->id,
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'gender' => ($faker->numberBetween(0, 1) == 0) ? 'male' : 'female',
        'status' => (($status = $faker->numberBetween(0, 4)) == 0) ? 'deactive' : 'active',
    ];
});
