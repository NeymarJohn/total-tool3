<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global_currencies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('currency_name');
            $table->string('currency_symbol');
            $table->string('currency_code');
            $table->double('exchange_rate')->nullable()->default(null);
            $table->double('usd_price')->nullable()->default(null);
            $table->enum('is_cryptocurrency',['yes', 'no'])->default('no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('global_currencies');
    }
}
