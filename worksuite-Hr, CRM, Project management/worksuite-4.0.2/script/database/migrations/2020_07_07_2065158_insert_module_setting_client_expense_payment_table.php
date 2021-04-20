<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertModuleSettingClientExpensePaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clientModules = ['expenses', 'payments'];
        foreach($clientModules as $moduleSetting){
                $modulesClient = new \App\ModuleSetting();
                $modulesClient->module_name = $moduleSetting;
                $modulesClient->type = 'client';
                $modulesClient->save();
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
        \App\ModuleSetting::where('type' ,'client')->where('module_name' ,'payments')->delete();
    }
}
