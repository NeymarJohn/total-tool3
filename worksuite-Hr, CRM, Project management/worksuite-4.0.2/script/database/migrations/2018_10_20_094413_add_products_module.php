<?php

use Illuminate\Database\Migrations\Migration;

class AddProductsModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleSetting = \App\ModuleSetting::where('module_name', 'products')->first();
        if(!$moduleSetting){
            $module = new \App\ModuleSetting();
            $module->module_name = 'products';
            $module->status = 'active';
            $module->save();
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
