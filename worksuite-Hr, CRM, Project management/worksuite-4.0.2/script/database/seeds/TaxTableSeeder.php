<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('taxes')->delete();

        $taxes = [
            'GST' => '10',
            'CGST' => '18',
            'VAT' => '10',
            'IGST' => '10',
            'UTGST' => '10',
        ];

        foreach ($taxes as $key => $value) {
            \App\Tax::create([
                'tax_name' => $key,
                'rate_percent' => $value,
            ]);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
