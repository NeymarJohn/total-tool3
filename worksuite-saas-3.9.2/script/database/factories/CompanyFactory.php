<?php

$factory->define(
    \App\Company::class,
    function(Faker\Generator $faker) {
        return [
            'company_name' => $faker->company,
            'company_email' => $faker->companyEmail,
            'company_phone' => $faker->phoneNumber,
            'address' => $faker->address,
            'website' => $faker->domainName,
        ];
    }
);
