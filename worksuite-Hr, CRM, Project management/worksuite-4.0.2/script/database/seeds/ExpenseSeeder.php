<?php

use Illuminate\Database\Seeder;
use App\Expense;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = env('SEED_RECORD_COUNT', 30);
        factory(\App\Expense::class, (int) $count)->create();
    }
}
