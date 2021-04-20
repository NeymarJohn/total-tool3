<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewUpdatePopupInGlobalSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->boolean('show_update_popup')->default(1);
        });
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('show_update_popup')->default(1);
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
            $table->dropColumn('show_update_popup');
        });

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('show_update_popup');
        });
    }
}
