<?php

use Illuminate\Database\Migrations\Migration;

class AlterFeatureSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE `features` CHANGE `type` `type` ENUM('image','icon','task','bills','team','apps') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'image';");
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
