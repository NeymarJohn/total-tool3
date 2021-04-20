<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\EmployeeDetails;
use Illuminate\Support\Facades\DB;

class AddJoiningDateColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->timestamp('joining_date')->useCurrent();
        });

        EmployeeDetails::whereNotNull('joining_date')
            ->update([
                "joining_date" => DB::raw("`created_at`"),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropColumn(['joining_date']);
        });
    }
}
