<?php

use App\EmployeeDetails;
use App\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmployeeDetailsOfDefaultAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::withoutGlobalScope('active')->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->select('users.id', 'users.company_id', 'users.email')
            ->where('roles.name', '<>', 'client')
            ->whereNotNull('users.company_id')
            ->with('employeeDetail')
            ->groupBy('users.id')
            ->get();
        foreach ($users as $user){
            if($user->employeeDetail){}
            else{
                $employee = new EmployeeDetails();
                $employee->user_id = $user->id;
                $employee->employee_id = 'emp-'.$user->id;
                $employee->company_id = $user->company_id;
                $employee->address = 'address';
                $employee->hourly_rate = '50';
                $employee->save();
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
        //
    }
}
