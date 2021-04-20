<?php

use Illuminate\Database\Migrations\Migration;

class AlterPackageMaxSizeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `packages` CHANGE `max_storage_size` `max_storage_size` INT(10) UNSIGNED NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `packages` CHANGE `max_file_size` `max_file_size` INT(10) UNSIGNED NULL DEFAULT NULL");
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
