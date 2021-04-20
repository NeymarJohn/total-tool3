<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\TicketType;

class AddLeadModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $moduleSetting = \App\ModuleSetting::where('module_name', 'leads')->first();
        if(!$moduleSetting){
            $module = new \App\ModuleSetting();
            $module->module_name = 'leads';
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

    }
}
