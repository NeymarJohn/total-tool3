<?php

use App\ProjectMember;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHourlyRateProjectMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('project_members', function (Blueprint $table) {
            $table->integer('hourly_rate');
        });

        $members = ProjectMember::with('user', 'user.employeeDetail')->get();
        foreach ($members as $key => $value) {
            $value->hourly_rate = ((!is_null($value->user->employeeDetail->hourly_rate)) ? $value->user->employeeDetail->hourly_rate : 0);
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
        Schema::table('project_members', function (Blueprint $table) {
            $table->dropColumn(['hourly_rate']);
        });
    }
}
