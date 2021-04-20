<?php
$designations = [
    'Trainee',
    'Senior',
    'Junior',
    'Team Lead',
    'Project Manager'
];

$factory->define(
    \App\Designation::class,
    function(Faker\Generator $faker) use($designations) {
        return [
            'name' => $faker->randomElement($designations)
        ];
    }
);
