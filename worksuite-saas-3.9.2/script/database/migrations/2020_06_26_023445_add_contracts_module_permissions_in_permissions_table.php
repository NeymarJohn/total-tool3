<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractsModulePermissionsInPermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permissions', function (Blueprint $table) {
            $module = \App\Module::where('module_name','contracts')->first();

            \App\Permission::create([
               'name' => 'add_contract',
               'display_name' => 'Add Contract',
               'module_id' => $module->id,
            ]);
            \App\Permission::create([
               'name' => 'edit_contract',
               'display_name' => 'Edit Contract',
               'module_id' => $module->id,
            ]);
            \App\Permission::create([
               'name' => 'view_contract',
               'display_name' => 'View Contract',
               'module_id' => $module->id,
            ]);
            \App\Permission::create([
               'name' => 'delete_contract',
               'display_name' => 'Delete Contract',
               'module_id' => $module->id,
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permissions', function (Blueprint $table) {
            //
        });
    }
}
