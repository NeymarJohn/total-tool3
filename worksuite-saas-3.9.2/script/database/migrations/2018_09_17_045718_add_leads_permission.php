<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Permission;
use App\Module;

class AddLeadsPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Module::insert([
            ['module_name' => 'leads']
        ]);
        $module = Module::where('module_name', 'leads')->first();

        Permission::insert([
            ['name' => 'add_lead', 'display_name' => 'Add Lead', 'module_id' => $module->id],
            ['name' => 'view_lead', 'display_name' => 'View Lead', 'module_id' => $module->id],
            ['name' => 'edit_lead', 'display_name' => 'Edit Lead', 'module_id' => $module->id],
            ['name' => 'delete_lead', 'display_name' => 'Delete Lead', 'module_id' => $module->id],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $module = Module::where('module_name', 'leads')->first();
        Permission::where('module_id', $module->id)->delete();
    }
}
