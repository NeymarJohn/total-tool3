<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDesignationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('company_id')->nullable();
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->timestamps();
        });

        Schema::table('employee_details', function (Blueprint $table) {
            $table->bigInteger('designation_id')->unsigned()->nullable()->default(null)->after('slack_username');
            $table->foreign('designation_id')->references('id')->on('designations')->onDelete('cascade')->onUpdate('cascade');
        });

        $companies = \App\Company::all();

        if($companies) {
            foreach($companies as $companyDetail) {

                $employees = \App\EmployeeDetails::whereNotNull('job_title')->where('company_id', $companyDetail->id)->groupBy('job_title')->get();

                if ($employees) {
                    foreach ($employees as $employee) {

                        $designation = \App\Designation::firstOrCreate(
                            [
                            'company_id' => $companyDetail->id,
                            'name' => trim($employee->job_title),
                            ]
                        );

                        $employee->designation_id = $designation->id;
                        $employee->save();
                    }
                }
            }
        }

        DB::statement("ALTER TABLE employee_details DROP COLUMN job_title;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designations');

        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropColumn(['designation_id']);
        });
    }
}
