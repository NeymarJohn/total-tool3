<?php

use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Module;

class AddHolidaysPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleCheck = Module::where('module_name', 'holidays')->first();

        if (!$moduleCheck){
            $module = new Module();
            $module->module_name = 'holidays';
            $module->save();
            $id = $module->id;
        }
        else{
            $id = $moduleCheck->id;
        }

        $permissionCheck = Permission::where('name', 'add_holiday')->first();

        if(!$permissionCheck){
            Permission::insert([
                ['name' => 'add_holiday', 'display_name' => 'Add Holiday', 'module_id' => $id],
                ['name' => 'view_holiday', 'display_name' => 'View Holiday', 'module_id' => $id],
                ['name' => 'edit_holiday', 'display_name' => 'Edit Holiday', 'module_id' => $id],
                ['name' => 'delete_holiday', 'display_name' => 'Delete Holiday', 'module_id' => $id],
            ]);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Permission::where('module_id', 15)->delete();
    }
}
