<?php

use Illuminate\Database\Migrations\Migration;

class AlterEmployeeDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `employee_details` CHANGE `hourly_rate` `hourly_rate` DOUBLE NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      //
    }
}
