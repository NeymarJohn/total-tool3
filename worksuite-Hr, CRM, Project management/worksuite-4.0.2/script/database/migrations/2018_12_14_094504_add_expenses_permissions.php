<?php

use Illuminate\Database\Migrations\Migration;
use App\Module;
use App\Permission;

class AddExpensesPermissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleCheck = Module::where('module_name', 'expenses')->first();

        if (!$moduleCheck){
            $module = new Module();
            $module->module_name = 'expenses';
            $module->save();
            $id = $module->id;
        }
        else{
            $id = $moduleCheck->id;
        }

        Permission::insert([
            ['name' => 'add_expenses', 'display_name' => 'Add Expenses', 'module_id' => $id],
            ['name' => 'view_expenses', 'display_name' => 'View Expenses', 'module_id' => $id],
            ['name' => 'edit_expenses', 'display_name' => 'Edit Expenses', 'module_id' => $id],
            ['name' => 'delete_expenses', 'display_name' => 'Delete Expenses', 'module_id' => $id],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $moduleCheck = Module::where('module_name', 'expenses')->first();
        if($moduleCheck){
            Permission::where('module_id', $moduleCheck->id)->delete();
        }
    }
}
