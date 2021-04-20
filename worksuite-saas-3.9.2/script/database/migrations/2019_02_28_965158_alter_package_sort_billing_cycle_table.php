<?php

use Illuminate\Database\Migrations\Migration;

class AlterPackageSortBillingCycleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `packages` CHANGE `sort` `sort` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL");
        DB::statement("ALTER TABLE `packages` CHANGE `billing_cycle` `billing_cycle` TINYINT(3) UNSIGNED NULL DEFAULT NULL");
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
