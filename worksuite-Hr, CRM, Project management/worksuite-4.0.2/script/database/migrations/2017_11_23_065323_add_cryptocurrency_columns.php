<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCryptocurrencyColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->enum('is_cryptocurrency', ['yes', 'no'])->default('no')->after('exchange_rate');
            $table->double('usd_price')->nullable()->after('is_cryptocurrency');
        });

        DB::statement('ALTER TABLE `currencies` CHANGE `currency_symbol` `currency_symbol` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('currencies', function (Blueprint $table) {
            $table->dropColumn(['is_cryptocurrency', 'usd_price']);
        });

        DB::statement('ALTER TABLE `currencies` CHANGE `currency_symbol` `currency_symbol` VARCHAR(191) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');
    }
}
