<?php

use Illuminate\Database\Migrations\Migration;
use App\Module;
use App\Permission;

class AddExpensesPermissionsDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleCheck = Module::where('module_name', 'expenses')->first();

        if ($moduleCheck){
            $moduleCheck->description = 'User can view and add(self expenses) the expenses as default even without any permission.';
            $moduleCheck->save();
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
