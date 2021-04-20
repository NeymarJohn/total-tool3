<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertModulesSettingClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $clientModules = ['contracts', 'notices'];
        foreach ($clientModules as $moduleSetting) {
            \App\ModuleSetting::firstOrCreate(
                [
                    'module_name' => $moduleSetting,
                    'type' => 'client',
                    'status' => 'active'
                ]
            );
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
