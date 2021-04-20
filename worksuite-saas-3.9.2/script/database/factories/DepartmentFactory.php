<?php
$departments = [
    'Marketing',
    'Sales',
    'Human Resources',
    'Public Relations',
    'Research',
    'Finance'
];

$factory->define(
    \App\Team::class,
    function(Faker\Generator $faker) use($departments) {
        return [
            'team_name' => $faker->randomElement($departments)
        ];
    }
);
