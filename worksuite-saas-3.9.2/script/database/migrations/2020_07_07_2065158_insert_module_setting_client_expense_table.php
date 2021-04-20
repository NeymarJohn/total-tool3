<?php

use App\Company;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertModuleSettingClientExpenseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = Company::select('id')->get();
        foreach($companies as $company){
            $clientModules = ['expenses'];
            foreach($clientModules as $moduleSetting){
                    $modulesClient = new \App\ModuleSetting();
                    $modulesClient->company_id = $company->id;
                    $modulesClient->module_name = $moduleSetting;
                    $modulesClient->type = 'client';
                    $modulesClient->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\ModuleSetting::where('type' ,'client')->where('module_name' ,'expenses')->delete();
    }
}
