<?php

use Illuminate\Database\Migrations\Migration;

class ChangeModuleIdNoticePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = \App\Module::Where('module_name', 'notices')->first();
        $permissions = \App\Permission::Where('name', 'like', '%_notice%')->get();
        foreach ($permissions as $permission){
            $permission->module_id = $module->id;
            $permission->save();
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
