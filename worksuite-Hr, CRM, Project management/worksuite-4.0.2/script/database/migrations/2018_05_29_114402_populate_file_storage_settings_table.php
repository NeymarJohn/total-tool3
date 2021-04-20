<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\StorageSetting;
use Illuminate\Support\Facades\DB;

class PopulateFileStorageSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `file_storage_settings` CHANGE `auth_keys` `auth_keys` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;');

        $storage = new StorageSetting();
        $storage->filesystem = 'local';
        $storage->status = 'enabled';
        $storage->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE `file_storage_settings` CHANGE `auth_keys` `auth_keys` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL;');

        StorageSetting::where('filesystem', 'local')->delete();
    }
}
