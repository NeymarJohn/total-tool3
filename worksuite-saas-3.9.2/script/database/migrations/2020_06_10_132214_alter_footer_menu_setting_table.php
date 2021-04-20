<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFooterMenuSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('footer_menu', function (Blueprint $table) {
            $table->string('external_link')->nullable()->default(null);
            $table->enum('type', ['header', 'footer', 'both'])->nullable()->default('footer');
            $table->enum('status', ['active', 'inactive'])->nullable()->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('footer_menu', function (Blueprint $table) {
            $table->dropColumn(['type']);
            $table->dropColumn(['status']);
            $table->dropColumn(['external_link']);
        });
    }
}
