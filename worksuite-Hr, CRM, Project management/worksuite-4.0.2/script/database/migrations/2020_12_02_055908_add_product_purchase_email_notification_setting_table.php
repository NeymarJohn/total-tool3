<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProductPurchaseEmailNotificationSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // When new product purchased by admin
        \App\EmailNotificationSetting::create([
            'setting_name' => 'New Product Purchase Request',
            'send_email' => 'yes',
            'slug' => 'new-product-purchase-request',
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \App\EmailNotificationSetting::where('slug', 'new-product-purchase-request')->delete();
    }
}
