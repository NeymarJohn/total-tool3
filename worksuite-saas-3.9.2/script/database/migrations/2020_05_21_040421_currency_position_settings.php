<?php

use App\Scopes\CompanyScope;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CurrencyPositionSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_currencies', function (Blueprint $table) {
            $table->enum('currency_position', ['front', 'behind'])->default('front');
        });
        Schema::table('currencies', function (Blueprint $table) {
            $table->enum('currency_position', ['front', 'behind'])->default('front');
        });
        // Global Currency Position
        $currencies = \App\GlobalCurrency::where('currency_code','EUR')->get();
        if($currencies){
            foreach ($currencies as $currency) {
                $currency->currency_position = 'behind';
                $currency->save();
            }
        }


        // Country Global Currency Position
        $currencies = \App\Currency::withoutGlobalScopes([CompanyScope::class,'enable'])->where('currency_code','EUR')->get();
        if($currencies) {
            foreach ($currencies as $currency) {
                $currency->currency_position = 'behind';
                $currency->save();
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
        Schema::table('global_currencies', function (Blueprint $table) {
            $table->dropColumn(['currency_position']);
        });
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn(['currency_position']);
        });

    }
}
