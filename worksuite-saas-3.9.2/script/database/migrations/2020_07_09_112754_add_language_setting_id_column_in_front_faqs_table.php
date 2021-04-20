<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageSettingIdColumnInFrontFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('front_faqs', function (Blueprint $table) {
            $table->unsignedInteger('language_setting_id')->nullable()->after('id');
            $table->foreign('language_setting_id')->references('id')->on('language_settings')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('front_faqs', function (Blueprint $table) {
            //
        });
    }
}
