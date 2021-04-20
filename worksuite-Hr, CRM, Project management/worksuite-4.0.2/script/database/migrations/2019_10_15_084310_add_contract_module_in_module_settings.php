<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractModuleInModuleSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = new \App\Module();
        $module->module_name = 'contracts';
        $module->description = 'User can view all contracts';
        $module->save();

        $modulesClient = new \App\ModuleSetting();
        $modulesClient->module_name = 'contracts';
        $modulesClient->type = 'client';
        $modulesClient->status = 'active';
        $modulesClient->save();

        $modulesClient = new \App\ModuleSetting();
        $modulesClient->module_name = 'contracts';
        $modulesClient->type = 'admin';
        $modulesClient->status = 'active';
        $modulesClient->save();
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
