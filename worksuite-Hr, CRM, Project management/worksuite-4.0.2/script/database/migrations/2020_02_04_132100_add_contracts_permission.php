<?php

use App\Module;
use App\Permission;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleCheck = Module::where('module_name', 'contracts')->first();
        if (!$moduleCheck){
            $module = new Module();
            $module->module_name = 'contracts';
            $module->save();
            $id = $module->id;
        }
        else{
            $id = $moduleCheck->id;
        }
        Permission::insert([
            ['name' => 'add_contract', 'display_name' => 'Add Contract', 'module_id' => $id],
            ['name' => 'view_contract', 'display_name' => 'View Contract', 'module_id' => $id],
            ['name' => 'edit_contract', 'display_name' => 'Edit Contract', 'module_id' => $id],
            ['name' => 'delete_contract', 'display_name' => 'Delete Contract', 'module_id' => $id],
        ]);
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
