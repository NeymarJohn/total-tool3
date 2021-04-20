<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGoogleRecaptchaColumnsInOrganisationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->boolean('google_recaptcha')->default(false);
            $table->string('google_recaptcha_key')->nullable()->default(null);
            $table->string('google_recaptcha_secret')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('organisation_settings', function (Blueprint $table) {
            $table->removeColumn('google_recaptcha');
            $table->removeColumn('google_recaptcha_key');
            $table->removeColumn('google_recaptcha_secret');
        });
    }
}
