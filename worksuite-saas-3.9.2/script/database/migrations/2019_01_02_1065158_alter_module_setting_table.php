<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterModuleSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_settings', function(Blueprint $table){
            $table->enum('type', ['admin','employee','client'])->default('admin')->after('status');
        });

//        $moduleSettings = \App\ModuleSetting::all();
//        foreach($moduleSettings as $moduleSetting){
//            $modules = new \App\ModuleSetting();
//            $modules->module_name = $moduleSetting->module_name;
//            $modules->type = 'employee';
//            $modules->save();
//            $modulesClient = new \App\ModuleSetting();
//            $modulesClient->module_name = $moduleSetting->module_name;
//            $modulesClient->type = 'client';
//            $modulesClient->save();
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_settings', function(Blueprint $table){
            $table->dropColumn(['type']);
        });
    }
}
