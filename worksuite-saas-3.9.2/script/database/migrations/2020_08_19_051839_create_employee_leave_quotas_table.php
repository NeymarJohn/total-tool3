<?php

use App\EmployeeDetails;
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

            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('leave_type_id')->unsigned();
            $table->foreign('leave_type_id')->references('id')->on('leave_types')->onDelete('cascade')->onUpdate('cascade');

            $table->integer('no_of_leaves');
            $table->timestamps();
        });

        $companies = \App\Company::all();

        if ($companies) {
            foreach ($companies as $company) {
                $leaveTypes = LeaveType::where('company_id', $company->id)->get();
                $employees = EmployeeDetails::where('company_id', $company->id)->get();

                foreach ($employees as $key => $employee) {
                    foreach ($leaveTypes as $key => $value) {
                        EmployeeLeaveQuota::create(
                            [
                                'company_id' => $company->id,
                                'user_id' => $employee->user_id,
                                'leave_type_id' => $value->id,
                                'no_of_leaves' => $value->no_of_leaves
                            ]
                        );
                    }
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
        Schema::dropIfExists('employee_leave_quotas');
    }
}
