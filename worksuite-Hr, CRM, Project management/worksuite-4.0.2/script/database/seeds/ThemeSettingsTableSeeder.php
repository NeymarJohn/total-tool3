<?php

use Illuminate\Database\Seeder;
use App\ThemeSetting;

class ThemeSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin panel
        $theme = new ThemeSetting();
        $theme->panel = "admin";
        $theme->header_color = "#ed4040";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();

        // project admin panel
        $theme = new ThemeSetting();
        $theme->panel = "project_admin";
        $theme->header_color = "#5475ed";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();

        // employee panel
        $theme = new ThemeSetting();
        $theme->panel = "employee";
        $theme->header_color = "#f7c80c";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();

        // client panel
        $theme = new ThemeSetting();
        $theme->panel = "client";
        $theme->header_color = "#00c292";
        $theme->sidebar_color = "#292929";
        $theme->sidebar_text_color = "#cbcbcb";
        $theme->save();

    }
}
