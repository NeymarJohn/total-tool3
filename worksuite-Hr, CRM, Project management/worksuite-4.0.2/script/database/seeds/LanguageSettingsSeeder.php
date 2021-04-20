<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = [
            ['language_code' => 'ar', 'language_name' => 'Arabic', 'status' => 'disabled'],
            ['language_code' => 'de', 'language_name' => 'German', 'status' => 'disabled'],
            ['language_code' => 'es', 'language_name' => 'Spanish', 'status' => 'enabled'],
            ['language_code' => 'et', 'language_name' => 'Estonian', 'status' => 'disabled'],
            ['language_code' => 'fa', 'language_name' => 'Farsi', 'status' => 'disabled'],
            ['language_code' => 'fr', 'language_name' => 'French', 'status' => 'enabled'],
            ['language_code' => 'gr', 'language_name' => 'Greek', 'status' => 'disabled'],
            ['language_code' => 'it', 'language_name' => 'Italian', 'status' => 'disabled'],
            ['language_code' => 'nl', 'language_name' => 'Dutch', 'status' => 'disabled'],
            ['language_code' => 'pl', 'language_name' => 'Polish', 'status' => 'disabled'],
            ['language_code' => 'pt', 'language_name' => 'Portuguese', 'status' => 'disabled'],
            ['language_code' => 'br', 'language_name' => 'Portuguese (Brazil)', 'status' => 'disabled'],
            ['language_code' => 'ro', 'language_name' => 'Romanian', 'status' => 'disabled'],
            ['language_code' => 'ru', 'language_name' => 'Russian', 'status' => 'enabled'],
            ['language_code' => 'tr', 'language_name' => 'Turkish', 'status' => 'disabled'],
            ['language_code' => 'zh-CN', 'language_name' => 'Chinese (S)', 'status' => 'disabled'],
            ['language_code' => 'zh-TW', 'language_name' => 'Chinese (T)', 'status' => 'disabled'],
        ];

        DB::table('language_settings')->insert($languages);
    }
}
