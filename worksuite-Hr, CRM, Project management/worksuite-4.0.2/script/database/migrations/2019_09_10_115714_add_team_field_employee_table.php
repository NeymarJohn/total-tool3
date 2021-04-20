<?php

use App\EmployeeDetails;
use App\Team;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTeamFieldEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->integer('department_id')->unsigned()->nullable()->default(null)->after('slack_username');
            $table->foreign('department_id')->references('id')->on('teams')->onDelete('SET NULL')->onUpdate('cascade');
        });

        $teams = Team::with('members')->get();
        foreach ($teams as $team) {
            if ($team->members) {
                foreach ($team->members as $member) {
                    EmployeeDetails::where('user_id', $member->user_id)->update(['department_id' => $member->team_id]);
                }
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropColumn(['department_id']);
        });
    }
}