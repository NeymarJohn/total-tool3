<?php

use Illuminate\Database\Seeder;

class ExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $company= \App\Company::first();
        try {
            factory(\App\Expense::class, 30)->create()->each(function ($expense) use ($company) {
                $expense->company_id = $company->id;
                $expense->save();
            });
        } catch (\Exception $e) {

        }

    }
}
