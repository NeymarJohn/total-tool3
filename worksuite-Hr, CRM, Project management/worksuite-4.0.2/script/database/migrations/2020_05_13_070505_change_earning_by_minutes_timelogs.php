<?php

use App\ProjectTimeLog;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeEarningByMinutesTimelogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $timelogs = ProjectTimeLog::where('total_minutes', '>', 0)->get();
        foreach ($timelogs as $key => $value) {
            $minuteRate = $value->hourly_rate / 60;
            $earning = round($value->total_minutes * $minuteRate);
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
        //
    }
}
