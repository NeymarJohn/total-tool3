<?php

use App\ProjectTimeLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHourlyRateProjectTimelogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->integer('hourly_rate');
            $table->integer('earnings');
        });

        $timelogs = ProjectTimeLog::with('user', 'user.employeeDetail')->get();
        foreach ($timelogs as $key => $value) {
            $value->hourly_rate = ((!is_null($value->user->employeeDetail->hourly_rate)) ? $value->user->employeeDetail->hourly_rate : 0);
            
            $hours = intdiv($value->total_minutes, 60);
            $earning = round($hours * $value->hourly_rate);

            $value->earnings = $earning;
            $value->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate']);
            $table->dropColumn(['earnings']);
        });
    }
}
