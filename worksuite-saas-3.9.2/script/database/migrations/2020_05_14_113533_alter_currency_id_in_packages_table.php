<?php

use App\GlobalSetting;
use App\Package;
use Illuminate\Database\Migrations\Migration;

class AlterCurrencyIdInPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $globalsSetting = GlobalSetting::with('currency')->first();
        if(!is_null($globalsSetting)){
            $packages = Package::whereNull('currency_id')->get();
            foreach($packages as $package){
                $package->currency_id = $globalsSetting->currency_id;
                $package->save();
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
