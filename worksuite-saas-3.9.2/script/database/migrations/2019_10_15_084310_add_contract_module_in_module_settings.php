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

        $companies = \App\Company::all();

        foreach($companies as $company) {
            $modulesClient = new \App\ModuleSetting();
            $modulesClient->module_name = 'contracts';
            $modulesClient->company_id = $company->id;
            $modulesClient->type = 'client';
            $modulesClient->status = 'active';
            $modulesClient->save();
        }

        $packages = \App\Package::all();
        foreach($packages as $package) {
            $modules = (array)json_decode($package->module_in_package);
            array_push($modules, 'contracts');
            $package->module_in_package = json_encode($modules);
            $package->save();
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
