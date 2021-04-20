<?php

use Illuminate\Database\Migrations\Migration;

class AlterInvoiceItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        DB::statement("ALTER TABLE `invoice_items` CHANGE `unit_price` `unit_price` DOUBLE(8,2) NOT NULL");
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
