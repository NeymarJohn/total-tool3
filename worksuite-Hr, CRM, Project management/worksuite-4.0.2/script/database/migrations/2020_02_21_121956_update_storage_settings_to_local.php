<?php

use App\StorageSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStorageSettingsToLocal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $setting = \App\StorageSetting::where('status','enabled')->first();

        if($setting->filesystem !== 'local' && $setting->filesystem !== 's3'){
            $storageData = StorageSetting::all();
            if(count($storageData) > 0) {
                foreach ($storageData as $data) {
                    $data->status = 'disabled';
                    $data->save();
                }
            }

            $storage = StorageSetting::firstorNew(['filesystem' => 'local']);
            $storage->status = 'enabled';
            $storage->save();
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('local', function (Blueprint $table) {
            //
        });
    }
}
