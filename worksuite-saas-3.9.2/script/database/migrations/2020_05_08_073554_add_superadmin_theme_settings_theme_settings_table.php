<?php

use App\ThemeSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuperadminThemeSettingsThemeSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->enum('active_theme', ['default', 'custom'])->default('default');
        });

        session()->forget('global_settings');

        $adminTheme = new ThemeSetting();
        $adminTheme->panel = "superadmin";
        $adminTheme->header_color = "#ed4040";
        $adminTheme->sidebar_color = "#292929";
        $adminTheme->sidebar_text_color = "#cbcbcb";
        $adminTheme->link_color = "#ffffff";
        $adminTheme->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn(['active_theme']);
        });
        ThemeSetting::where('panel', 'superadmin')->delete();
    }
}
