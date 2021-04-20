<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompanyCurrencyApiGoogleApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('organisation_settings')){
            Schema::table('organisation_settings', function (Blueprint $table) {
                $table->string('google_map_key')->nullable()->default(null)->after('last_updated_by');
                $table->string('currency_converter_key')->nullable()->default(null)->after('last_updated_by');
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(Schema::hasTable('organisation_settings')) {
            Schema::table('organisation_settings', function (Blueprint $table) {
                $table->dropColumn('google_map_key');
                $table->dropColumn('currency_converter_key');
            });
        }
    }
}
