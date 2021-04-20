<?php

use App\EmployeeLeaveQuota;
use App\LeaveType;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeLeaveQuotasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_quotas', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('leave_type_id')->unsigned();
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('no_of_leaves');
            $table->timestamps();
        });

        $leaveTypes = LeaveType::all();
        $employees = \App\EmployeeDetails::select('id','user_id')->get();

        foreach ($employees as $key => $employee) {
            foreach ($leaveTypes as $key => $value) {
                EmployeeLeaveQuota::create(
                    [
                        'user_id' => $employee->user_id,
                        'leave_type_id' => $value->id,
                        'no_of_leaves' => $value->no_of_leaves
                    ]
                );
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
        Schema::dropIfExists('employee_leave_quotas');
    }
}
