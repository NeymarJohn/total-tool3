<?php

use Illuminate\Database\Migrations\Migration;

class AlterInvoiceProjectIdNullTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `invoices` CHANGE `project_id` `project_id` INT(10) UNSIGNED NULL DEFAULT NULL");
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
