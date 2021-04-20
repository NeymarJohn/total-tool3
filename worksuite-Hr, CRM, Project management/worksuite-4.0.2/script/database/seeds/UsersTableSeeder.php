<?php

use App\Team;
use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\App;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run() {

        \DB::table('users')->delete();
        \DB::table('employee_details')->delete();
        \DB::table('universal_search')->delete();

        \DB::statement('ALTER TABLE users AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE employee_details AUTO_INCREMENT = 1');
        \DB::statement('ALTER TABLE universal_search AUTO_INCREMENT = 1');

        $count = env('SEED_RECORD_COUNT', 30);

        $faker = \Faker\Factory::create();

        $user = new User();
        $user->name = $faker->name;
        $user->email = 'admin@example.com';
        $user->password = Hash::make('123456');
        $user->save();

        $employee = new \App\EmployeeDetails();
        $employee->user_id = $user->id;
        $employee->employee_id = 'emp-'.$user->id;
        $employee->address = $faker->address;
        $employee->hourly_rate = $faker->numberBetween(15, 100);
        $employee->save();

        $search = new \App\UniversalSearch();
        $search->searchable_id = $user->id;
        $search->title = $user->name;
        $search->route_name = 'admin.employees.show';
        $search->save();

        $adminRole = \App\Role::where('name', 'admin')->first();
        $employeeRole = \App\Role::where('name', 'employee')->first();
        $clientRole = \App\Role::where('name', 'client')->first();

        $user->roles()->attach($adminRole->id); // id only
        $user->roles()->attach($employeeRole->id); // id only

        if (!App::environment('codecanyon')) {
            // Employee details

            $this->call(DepartmentTableSeeder::class);

            $user = new User();
            $user->name = $faker->name;
            $user->email = 'employee@example.com';
            $user->password = Hash::make('123456');
            $user->save();

            $search = new \App\UniversalSearch();
            $search->searchable_id = $user->id;
            $search->title = $user->name;
            $search->route_name = 'admin.employees.show';
            $search->save();

            $employee = new \App\EmployeeDetails();
            $employee->user_id = $user->id;
            $employee->employee_id = 'emp-'.$user->id;
            $employee->address = $faker->address;
            $employee->department_id = $faker->randomElement($this->getDepartment());
            $employee->designation_id = $faker->randomElement($this->getDesignation());
            $employee->hourly_rate = $faker->numberBetween(15, 100);
            $employee->save();

            // Assign Role
            $user->roles()->attach($employeeRole->id);

            // Client details
            $user = new User();
            $user->name = $faker->name;
            $user->email = 'client@example.com';
            $user->password = Hash::make('123456');
            $user->save();

            $search = new \App\UniversalSearch();
            $search->searchable_id = $user->id;
            $search->title = $user->name;
            $search->route_name = 'admin.clients.show';
            $search->save();

            $client = new \App\ClientDetails();
            $client->user_id = $user->id;
            $client->company_name = $faker->company;
            $client->address = $faker->address;
            $client->website = $faker->url;
            $client->save();

            // Assign Role
            $user->roles()->attach($clientRole->id);

            // // Multiple admin create
            // factory(\App\User::class, (int) $count)->create()->each(function ($user) use($faker, $adminRole) {
            //     $employee = new \App\EmployeeDetails();
            //     $employee->user_id = $user->id;
            //     $employee->employee_id = 'emp-'.$user->id;
            //     $employee->address = $faker->address;
            //     $employee->hourly_rate = $faker->numberBetween(15, 100);
            //     $employee->department_id = $faker->randomElement($this->getDepartment());
            //     $employee->designation_id = $faker->randomElement($this->getDesignation());
            //     $employee->save();

            //     $search = new \App\UniversalSearch();
            //     $search->searchable_id = $user->id;
            //     $search->title = $user->name;
            //     $search->route_name = 'admin.employees.show';
            //     $search->save();

            //     // Assign Role
            //     $user->roles()->attach($adminRole->id);
            // });

            // Multiple client create
            factory(User::class, (int) $count)->create()->each(function ($user) use($faker, $clientRole) {
                $search = new \App\UniversalSearch();
                $search->searchable_id = $user->id;
                $search->title = $user->name;
                $search->route_name = 'admin.clients.show';
                $search->save();

                $client = new \App\ClientDetails();
                $client->user_id = $user->id;
                $client->company_name = $faker->company;
                $client->address = $faker->address;
                $client->website = $faker->url;
                $client->save();

                // Assign Role
                $user->roles()->attach($clientRole->id);

            });

            // Multiple employee create
            factory(User::class, (int) $count)->create()->each(function ($user) use($faker, $employeeRole) {
                $search = new \App\UniversalSearch();
                $search->searchable_id = $user->id;
                $search->title = $user->name;
                $search->route_name = 'admin.employees.show';
                $search->save();

                $employee = new \App\EmployeeDetails();
                $employee->user_id = $user->id;
                $employee->employee_id = 'emp-'.$user->id;
                $employee->address = $faker->address;
                $employee->hourly_rate = $faker->numberBetween(15, 100);
                $employee->department_id = $faker->randomElement($this->getDepartment());
                $employee->designation_id = $faker->randomElement($this->getDesignation());
                $employee->hourly_rate = $faker->numberBetween(15, 100);
                $employee->save();

                // Assign Role
                $user->roles()->attach($employeeRole->id);
            });
        }
    }

    public function getDepartment()
    {
        return Team::inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

    public function getDesignation()
    {
        return \App\Designation::inRandomOrder()
            ->get()->pluck('id')->toArray();
    }

}
