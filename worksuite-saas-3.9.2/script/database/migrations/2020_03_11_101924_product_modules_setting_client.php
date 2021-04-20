<?php

use App\Company;
use App\ModuleSetting;
use App\Scopes\CompanyScope;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ProductModulesSettingClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $companies = Company::get();
        foreach($companies as $company){
            $moduleSettings = ModuleSetting::where('type', 'client')->where('module_name', 'products')->where('company_id', $company->id)
                ->withoutGlobalScope(CompanyScope::class)->get();
            if(count($moduleSettings) == 0){
                $moduleSetting = new ModuleSetting();
                    $moduleSetting->company_id = $company->id;
                    $moduleSetting->module_name = 'products';
                    $moduleSetting->status = 'active';
                    $moduleSetting->type = 'client';
                    $moduleSetting->save();
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
        //
    }
}
