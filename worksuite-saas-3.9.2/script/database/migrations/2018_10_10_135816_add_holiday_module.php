<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TicketType;

class AddHolidayModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        $moduleSetting = \App\ModuleSetting::where('module_name', 'holidays')->first();
//        if(!$moduleSetting){
//            $module = new \App\ModuleSetting();
//            $module->module_name = 'holidays';
//            $module->status = 'active';
//            $module->save();
//        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
