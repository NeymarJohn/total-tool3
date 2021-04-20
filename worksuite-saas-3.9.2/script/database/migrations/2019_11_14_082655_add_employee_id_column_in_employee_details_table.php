<?php

use App\EmployeeDetails;
use App\Scopes\CompanyScope;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeIdColumnInEmployeeDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->string('employee_id')->nullable()->unique()->default(null)->after('user_id');
        });
        $employeeDetails = EmployeeDetails::withoutGlobalScope(CompanyScope::class)->get();
        if ($employeeDetails->count() > 0){
            foreach ($employeeDetails as $employeeDetail){
                $employeeDetail->employee_id = 'emp-'.$employeeDetail->id;
                $employeeDetail->save();
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
            $table->dropColumn('employee_id');
        });
    }
}
