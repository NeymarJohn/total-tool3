<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;
use Illuminate\Support\Facades\App;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run() {

        // Assign Admin role for each company
        $faker = \Faker\Factory::create();
        $companies = \App\Company::all();

        $companies->each(function ($company) use ( $faker) {

            $admin = Role::where('company_id', $company->id)->where('name', 'admin')->first();
            $employee = Role::where('company_id', $company->id)->where('name', 'employee')->first();
            $client = Role::where('company_id', $company->id)->where('name', 'client')->first();

            if($company->id != 1) {
                $user = User::where('email', '=', 'admin'.$company->id.'@example.com')
                    ->where('company_id', $company->id)
                    ->whereNotNull('company_id')
                    ->first();
                // Assign admin Role
                if($user->company_id == $company->id) {
                    $user->roles()->attach($admin->id);
                }

                if (!App::environment('codecanyon')) {
                    // Attach employee role to existing users
                    $users = User::where('email', '<>', 'client'.$company->id.'@example.com')->where('company_id', $company->id)->get();

                    foreach ($users as $user) {
                        $user->roles()->attach($employee->id);
                    }

                    // Assign client Role
                    $user = User::where('email', '=', 'client'.$company->id.'@example.com')->where('company_id', $company->id)->first();
                    $user->roles()->attach($client->id); // id only
                }
            }
            else{

                $user = User::where('email', '=', 'admin@example.com')->first();
                // Assign admin Role
                if($user->company_id == $company->id) {
                    $user->roles()->attach($admin->id);
                }

                if($company->id != 1) {
                    $user = User::where('company_id', $company->id)->first();
                    $user->roles()->attach($admin->id);
                }

                if (App::environment('codecanyon')) {
                    $user->roles()->attach($employee->id); // id only
                }

                if (!App::environment('codecanyon')) {
                    // Attach employee role to existing users
                    $users = User::where('email', '<>', 'client@example.com')->where('company_id', $company->id)->get();

                    foreach ($users as $user) {
                        $user->roles()->attach($employee->id);
                    }

                    // Assign client Role
                    $user = User::where('email', '=', 'client@example.com')->first();
                    $user->roles()->attach($client->id); // id only
                }
            }

        });
    }
}
