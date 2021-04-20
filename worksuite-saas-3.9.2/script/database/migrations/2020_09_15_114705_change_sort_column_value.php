<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeSortColumnValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $packages = \App\Package::all();
        foreach($packages as $package){
            if(!is_int($package->sort) || is_null($package->sort)){
                $package->sort = 0;
            }
            $package->save();
        }
        DB::statement("ALTER TABLE `packages` CHANGE `sort` `sort` INT(11) NULL DEFAULT NULL;");
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
