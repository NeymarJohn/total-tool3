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
        DB::table('custom_field_groups')->insert(
            [
                'name' => 'Invoice', 'model' => 'App\Invoice',
            ]
        );
        DB::table('custom_field_groups')->insert(
            [
                'name' => 'Estimate', 'model' => 'App\Estimate',
            ]
        );
        DB::table('custom_field_groups')->insert(
            [
                'name' => 'Task', 'model' => 'App\Task',
            ]
        );
        DB::table('custom_field_groups')->insert(
            [
                'name' => 'Expense', 'model' => 'App\Expense',
            ]
        );
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
    }
}
