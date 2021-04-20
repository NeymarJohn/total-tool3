<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficeOpenDaysColumnAttendanceSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $json = json_encode([1,2,3,4,5]);
        Schema::table('attendance_settings', function (Blueprint $table) use ($json){
            $table->string('office_open_days')->default($json)->after('employee_clock_in_out');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_settings', function (Blueprint $table) {
            $table->dropColumn(['office_open_days']);
        });
    }
}
