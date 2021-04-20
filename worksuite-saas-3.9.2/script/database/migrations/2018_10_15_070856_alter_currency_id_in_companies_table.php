<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCurrencyIdInCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE companies DROP FOREIGN KEY organisation_settings_currency_id_foreign ');

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('currency_id');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->unsignedInteger('currency_id')->nullable()->after('website');
            $table->foreign('currency_id')->references('id')->on('currencies')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropForeign('currency_id');
            $table->dropColumn('currency_id');
        });
    }
}
