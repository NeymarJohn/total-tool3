<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCurrencyIdInGlobalSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->nullable()->default(null)->after('id');
            $table->foreign('currency_id')->references('id')->on('global_currencies')->onDelete(null)->onUpdate('cascade');
        });

        Schema::table('packages', function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->nullable()->default(null)->after('id');
            $table->foreign('currency_id')->references('id')->on('global_currencies')->onDelete(null)->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropForeign('currency_id');
            $table->dropColumn('currency_id');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->dropForeign('currency_id');
            $table->dropColumn('currency_id');
        });
    }
}
