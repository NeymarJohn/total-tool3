<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('products')->delete();

        $count = env('SEED_PROJECT_RECORD_COUNT', 20);
        $faker = \Faker\Factory::create();

        factory(\App\Product::class, (int) $count)->create();
    }

    public function getTaxes()
    {
        return \App\Tax::get()->pluck('id')->toArray();
    }
}
