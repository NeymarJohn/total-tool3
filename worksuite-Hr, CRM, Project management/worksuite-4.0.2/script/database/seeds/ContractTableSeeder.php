<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('contracts')->delete();

        $count = env('SEED_PROJECT_RECORD_COUNT', 20);
        $faker = \Faker\Factory::create();

        factory(\App\Contract::class, (int) $count)->create()->each(function ($contract) use($faker, $count) {
            $contract->contract_type_id = $faker->randomElement($this->getContractType());
            $contract->client_id = $faker->randomElement($this->getClient());
            $contract->save();
        });

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function getContractType()
    {
        return \App\ContractType::inRandomOrder()->get()->pluck('id')->toArray();
    }

    public function getClient()
    {
        return \App\User::join('role_user', 'role_user.user_id', '=', 'users.id')
            ->leftJoin('client_details', 'users.id', '=', 'client_details.user_id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.name', 'client_details.company_name', 'users.email', 'users.created_at')
            ->where('roles.name', 'client')
            ->inRandomOrder()
            ->get()->pluck('id')->toArray();
    }
}
