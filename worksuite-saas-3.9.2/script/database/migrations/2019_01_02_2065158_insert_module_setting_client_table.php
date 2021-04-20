<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertModuleSettingClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        \App\ModuleSetting::where('type' ,'client')->delete();
        $clientModules = ['projects', 'tickets', 'invoices', 'estimates', 'events', 'messages'];
//        foreach($clientModules as $moduleSetting){
//                $modulesClient = new \App\ModuleSetting();
//                $modulesClient->module_name = $moduleSetting;
//                $modulesClient->type = 'client';
//                $modulesClient->save();
//        }
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
