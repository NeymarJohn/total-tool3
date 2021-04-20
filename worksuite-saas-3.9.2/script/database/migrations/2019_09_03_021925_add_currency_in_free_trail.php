<?php

use App\GlobalSetting;
use App\Package;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyInFreeTrail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $package = Package::where('default', 'trial')->first();
        if ($package) {
            $global = GlobalSetting::with('currency')->first();

            if ($global) {
                $package->currency_id = $global->currency_id;
            }
            $package->save();
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
