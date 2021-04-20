<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFrontSettingCustomCssThemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('front_details', function (Blueprint $table) {
            $table->longText('custom_css')->nullable()->default(null);
            $table->longText('custom_css_theme_two')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('front_details', function (Blueprint $table) {
            $table->dropColumn(['custom_css']);
            $table->dropColumn(['custom_css_theme_two']);
        });
    }
}
