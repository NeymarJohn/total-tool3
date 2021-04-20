<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $module = new \App\Module();
        $module->module_name = 'reports';
        $module->description = 'Report module';
        $module->save();

        $companies = \App\Company::all();

        foreach($companies as $company) {
            $modulesClient = new \App\ModuleSetting();
            $modulesClient->module_name = 'reports';
            $modulesClient->company_id = $company->id;
            $modulesClient->type = 'admin';
            $modulesClient->status = 'active';
            $modulesClient->save();
        }

        $packages = \App\Package::all();
        foreach($packages as $package) {
            $modules = (array)json_decode($package->module_in_package);
            array_push($modules, 'reports');
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
