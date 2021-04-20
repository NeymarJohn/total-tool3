<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\ProjectTimeLog;

class AddMinutesColumnTimeLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_time_logs', function (Blueprint $table) {
            $table->string('total_minutes')->nullable()->after('total_hours');
        });

        $timeLogs = ProjectTimeLog::all();
        foreach ($timeLogs as $timeLog){
            $row = ProjectTimeLog::find($timeLog->id);
            $row->total_hours = $row->end_time->diff($row->start_time)->format('%d')*24+$row->end_time->diff($row->start_time)->format('%H');

            $minutes = $row->total_minutes = ($row->total_hours*60)+($row->end_time->diff($row->start_time)->format('%i'));

            if (($minutes % 2 == 0) && (substr($minutes,-1) != 0)) {
                $minutes++;
            }

            $row->total_minutes = $minutes;
            $row->save();
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
            $table->dropColumn(['total_minutes']);
        });
    }
}
