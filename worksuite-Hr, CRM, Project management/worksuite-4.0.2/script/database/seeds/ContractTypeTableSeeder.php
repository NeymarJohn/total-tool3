<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContractTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('contract_types')->delete();

        $contracts = [
            "Adhesion Contracts",
            "Aleatory Contracts",
            "Bilateral and Unilateral Contracts",
            "Contracts under Seal",
            "Executed and Executory Contracts",
            "Express Contracts",
            "Implied Contracts",
            "Unconscionable Contracts",
            "Void and Voidable Contracts"
        ];

        foreach ($contracts as $value) {
            \App\ContractType::create([
                'name' => $value,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
