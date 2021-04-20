<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\File;

class CheckVerifyPurchaseFile extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $legalFile = storage_path().'/legal';
        if(file_exists($legalFile)) {
            $legalFileInfo = File::get($legalFile);

            $legalFileInfo = explode('**', $legalFileInfo);
            $domain = $legalFileInfo[0];
            $purchaseCode = $legalFileInfo[1];

            if($purchaseCode == 'froiden-dev'){
                File::delete($legalFile);
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
