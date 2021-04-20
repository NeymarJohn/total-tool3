<?php

use Illuminate\Database\Migrations\Migration;

class AlterAttendanceSettingDefaultTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `attendance_settings` CHANGE `clockin_in_day` `clockin_in_day` INT(11) NOT NULL DEFAULT '1'");
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
