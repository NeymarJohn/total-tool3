<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddCustomFieldGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies =  \App\Company::withoutGlobalScope('active')->get();
        foreach ($companies as $company) {
            DB::table('custom_field_groups')->insert(
                [
                    'company_id' => $company->id, 'name' => 'Invoice', 'model' => 'App\Invoice',
                ]
            );
            DB::table('custom_field_groups')->insert(
                [
                    'company_id' => $company->id, 'name' => 'Estimate', 'model' => 'App\Estimate',
                ]
            );
            DB::table('custom_field_groups')->insert(
                [
                    'company_id' => $company->id, 'name' => 'Task', 'model' => 'App\Task',
                ]
            );
            DB::table('custom_field_groups')->insert(
                [
                    'company_id' => $company->id, 'name' => 'Expense', 'model' => 'App\Expense',
                ]
            );
            DB::table('custom_field_groups')->insert(
                [
                    'company_id' => $company->id, 'name' => 'Lead', 'model' => 'App\Lead',
                ]
            );
        }

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('custom_field_groups')->where('name', 'Invoice')->delete();
        DB::table('custom_field_groups')->where('name', 'Estimate')->delete();
        DB::table('custom_field_groups')->where('name', 'Task')->delete();
        DB::table('custom_field_groups')->where('name', 'Expense')->delete();
        DB::table('custom_field_groups')->where('name', 'Lead')->delete();
    }
}
